<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateSaleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'imeis' => ['required', 'array', 'min:1'],
            'imeis.*' => ['required', 'array'],
            'imeis.*.1' => ['required', 'digits:15'],
            'imeis.*.2' => ['nullable', 'digits:15'],

            'price' => ['sometimes', 'numeric', 'min:0'],
            'fixed_discount' => ['nullable', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
            'qty' => ['sometimes', 'integer', 'min:1'],

            'customer_id' => ['required', 'exists:customers,id'],
            'brand_id' => ['sometimes', 'exists:brands,id'],
            'product_id' => ['sometimes', 'exists:products,id'],
        ];
    }
}
