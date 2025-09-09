<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CreateSaleReturnRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'sale_id'       => ['required', 'exists:sales,id'],

            'imeis'         => ['required', 'array', 'min:1'],
            'imeis.*'       => ['required', 'array'],
            'imeis.*.1'     => ['required', 'digits:15'],
            'imeis.*.2'     => ['nullable', 'digits:15'],

            'price'         => ['required', 'numeric', 'min:0'],
            'fixed_discount' => ['nullable', 'numeric', 'min:0'],
            'reason'        => ['nullable', 'string'],

            'customer_id'   => ['required', 'exists:customers,id'],
            'brand_id'      => ['required', 'exists:brands,id'],
            'product_id'    => ['required', 'exists:products,id'],
        ];
    }
}
