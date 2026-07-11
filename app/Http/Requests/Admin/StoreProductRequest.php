<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Enums\ProductStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->isAdmin();
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'category_id' => ['required', 'integer', Rule::exists('categories', 'id')->whereNull('deleted_at')],
            'name' => ['required', 'string', 'max:200'],
            'slug' => ['nullable', 'string', 'max:220', 'alpha_dash', Rule::unique('products', 'slug')],
            'sku' => ['nullable', 'string', 'max:40', Rule::unique('products', 'sku')],
            'description' => ['nullable', 'string', 'max:10000'],
            'price' => ['required', 'numeric', 'min:0', 'max:9999999'],
            'compare_at_price' => ['nullable', 'numeric', 'gt:price', 'max:9999999'],
            'stock' => ['required', 'integer', 'min:0'],
            'status' => ['required', Rule::enum(ProductStatus::class)],
            'featured' => ['boolean'],
            'thumbnail' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'images' => ['array', 'max:6'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'variants' => ['array', 'max:20'],
            'variants.*.id' => ['nullable', 'integer', Rule::exists('product_variants', 'id')],
            'variants.*.name' => ['required', 'string', 'max:50'],
            'variants.*.value' => ['required', 'string', 'max:100'],
            'variants.*.price_modifier' => ['required', 'numeric', 'min:0', 'max:9999999'],
            'variants.*.stock' => ['required', 'integer', 'min:0'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'featured' => $this->boolean('featured'),
            // Drop rows the admin added but left completely empty.
            'variants' => collect($this->input('variants', []))
                ->filter(fn (array $variant): bool => filled($variant['name'] ?? null) || filled($variant['value'] ?? null))
                ->values()
                ->all(),
        ]);
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'category_id' => 'หมวดหมู่',
            'name' => 'ชื่อสินค้า',
            'price' => 'ราคา',
            'compare_at_price' => 'ราคาก่อนลด',
            'stock' => 'จำนวนสต็อก',
            'thumbnail' => 'รูปหลัก',
            'images.*' => 'รูปภาพ',
        ];
    }
}
