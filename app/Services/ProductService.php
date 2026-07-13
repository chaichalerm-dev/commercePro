<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Support\HomeCache;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductService
{
    private const IMAGE_DIR = 'products';

    /**
     * @param  array<string, mixed>  $data  validated product attributes
     * @param  list<UploadedFile>  $gallery
     * @param  list<array<string, mixed>>  $variants
     */
    public function create(array $data, ?UploadedFile $thumbnail, array $gallery = [], array $variants = []): Product
    {
        return DB::transaction(function () use ($data, $thumbnail, $gallery, $variants): Product {
            if ($thumbnail !== null) {
                $data['thumbnail'] = $this->storeImage($thumbnail);
            }

            /** @var Product $product */
            $product = Product::query()->create($data);

            $this->appendGallery($product, $gallery);
            $this->syncVariants($product, $variants);

            ActivityLog::record('product.created', $product, ['name' => $product->name]);
            HomeCache::forget();

            return $product;
        });
    }

    /**
     * @param  array<string, mixed>  $data
     * @param  list<UploadedFile>  $gallery  new images to append
     * @param  list<array<string, mixed>>  $variants
     * @param  list<int>  $removedImageIds
     */
    public function update(Product $product, array $data, ?UploadedFile $thumbnail, array $gallery = [], array $variants = [], array $removedImageIds = []): Product
    {
        return DB::transaction(function () use ($product, $data, $thumbnail, $gallery, $variants, $removedImageIds): Product {
            if ($thumbnail !== null) {
                $this->deleteImageFile($product->thumbnail);
                $data['thumbnail'] = $this->storeImage($thumbnail);
            }

            $product->fill($data);

            // Blank slug submitted + renamed => regenerate from the new name.
            if (blank($data['slug'] ?? null) && $product->isDirty('name')) {
                $product->setAttribute('slug', $product->generateUniqueSlug());
            }

            $product->save();

            if ($removedImageIds !== []) {
                $product->images()->whereKey($removedImageIds)->get()->each(function ($image): void {
                    $this->deleteImageFile($image->path);
                    $image->delete();
                });
            }

            $this->appendGallery($product, $gallery);
            $this->syncVariants($product, $variants);

            ActivityLog::record('product.updated', $product, ['name' => $product->name, 'changes' => array_keys($product->getChanges())]);
            HomeCache::forget();

            return $product->refresh();
        });
    }

    public function delete(Product $product): void
    {
        $product->delete();

        ActivityLog::record('product.deleted', $product, ['name' => $product->name]);
        HomeCache::forget();
    }

    public function restore(Product $product): void
    {
        $product->restore();

        ActivityLog::record('product.restored', $product, ['name' => $product->name]);
        HomeCache::forget();
    }

    public function toggleFeatured(Product $product): Product
    {
        $product->update(['featured' => ! $product->featured]);

        ActivityLog::record('product.featured_toggled', $product, ['featured' => $product->featured]);
        HomeCache::forget();

        return $product;
    }

    /**
     * @param  list<UploadedFile>  $gallery
     */
    protected function appendGallery(Product $product, array $gallery): void
    {
        $nextOrder = (int) $product->images()->max('sort_order') + 1;

        foreach ($gallery as $index => $file) {
            $product->images()->create([
                'path' => $this->storeImage($file),
                'sort_order' => $nextOrder + $index,
            ]);
        }
    }

    /**
     * Update submitted variants by id, create rows without one, and delete
     * any existing variant the admin removed from the form.
     *
     * @param  list<array<string, mixed>>  $variants
     */
    protected function syncVariants(Product $product, array $variants): void
    {
        $keptIds = [];

        foreach ($variants as $variant) {
            $attributes = [
                'name' => $variant['name'],
                'value' => $variant['value'],
                'price_modifier' => $variant['price_modifier'] ?? 0,
                'stock' => $variant['stock'] ?? 0,
            ];

            if (filled($variant['id'] ?? null)) {
                $existing = $product->variants()->whereKey($variant['id'])->first();

                if ($existing !== null) {
                    $existing->update($attributes);
                    $keptIds[] = $existing->id;

                    continue;
                }
            }

            $keptIds[] = $product->variants()->create([
                ...$attributes,
                'sku' => $this->generateVariantSku(),
            ])->id;
        }

        $product->variants()->whereKeyNot($keptIds)->delete();
    }

    protected function storeImage(UploadedFile $file): string
    {
        return $file->store(self::IMAGE_DIR, config('filesystems.default'));
    }

    /**
     * Remove a stored file; external URLs (seed data) have nothing to delete.
     */
    protected function deleteImageFile(?string $path): void
    {
        if (filled($path) && ! Str::startsWith($path, ['http://', 'https://'])) {
            Storage::disk(config('filesystems.default'))->delete($path);
        }
    }

    protected function generateVariantSku(): string
    {
        do {
            $sku = 'V-'.strtoupper(Str::random(8));
        } while (ProductVariant::query()->where('sku', $sku)->exists());

        return $sku;
    }
}
