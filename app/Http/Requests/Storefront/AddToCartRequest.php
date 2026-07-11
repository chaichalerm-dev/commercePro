<?php

declare(strict_types=1);

namespace App\Http\Requests\Storefront;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AddToCartRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // guests may add to cart
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'product_id' => ['required', 'integer', Rule::exists('products', 'id')->whereNull('deleted_at')],
            'variant_id' => ['nullable', 'integer', Rule::exists('product_variants', 'id')->where('product_id', $this->integer('product_id'))],
            'qty' => ['required', 'integer', 'min:1', 'max:99'],
        ];
    }
}
