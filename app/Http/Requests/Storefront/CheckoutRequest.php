<?php

declare(strict_types=1);

namespace App\Http\Requests\Storefront;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * Either an existing address id (owned by the user) or a full new
     * address must be supplied.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'address_id' => [
                'nullable',
                'integer',
                Rule::exists('addresses', 'id')->where('user_id', $this->user()->id),
            ],
            'recipient' => ['required_without:address_id', 'nullable', 'string', 'max:100'],
            'phone' => ['required_without:address_id', 'nullable', 'string', 'max:20'],
            'line1' => ['required_without:address_id', 'nullable', 'string', 'max:255'],
            'district' => ['required_without:address_id', 'nullable', 'string', 'max:100'],
            'province' => ['required_without:address_id', 'nullable', 'string', 'max:100'],
            'postal_code' => ['required_without:address_id', 'nullable', 'string', 'max:10'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'recipient' => __('storefront/checkout.fields.recipient'),
            'phone' => __('storefront/checkout.fields.phone'),
            'line1' => __('storefront/checkout.fields.line1'),
            'district' => __('storefront/checkout.fields.district'),
            'province' => __('storefront/checkout.fields.province'),
            'postal_code' => __('storefront/checkout.fields.postal_code'),
        ];
    }
}
