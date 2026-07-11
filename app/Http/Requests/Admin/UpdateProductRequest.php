<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Validation\Rule;

class UpdateProductRequest extends StoreProductRequest
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $product = $this->route('product');

        return array_merge(parent::rules(), [
            'slug' => ['nullable', 'string', 'max:220', 'alpha_dash', Rule::unique('products', 'slug')->ignore($product)],
            'sku' => ['nullable', 'string', 'max:40', Rule::unique('products', 'sku')->ignore($product)],
            // Ids of existing gallery images the admin removed in the form.
            'removed_images' => ['array'],
            'removed_images.*' => ['integer', Rule::exists('product_images', 'id')->where('product_id', $product->id)],
        ]);
    }
}
