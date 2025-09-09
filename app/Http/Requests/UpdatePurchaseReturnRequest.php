<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdatePurchaseReturnRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    protected function prepareForValidation(): void
    {
        if ($this->filled('party_type_id')) {
            [$type, $id] = explode('|', $this->input('party_type_id'));
            $this->merge([
                'party_type' => $type,
                'party_id'   => (int) $id,
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'purchase_id' => ['sometimes', 'exists:purchases,id'],
            'imeis'       => ['required', 'array', 'min:1'],
            'imeis.*'     => ['required', 'array'],
            'imeis.*.1'   => ['required', 'digits:15'],
            'imeis.*.2'   => ['nullable', 'digits:15'],
            'price'       => ['sometimes', 'numeric', 'min:0'],
            'fixed_discount' => ['nullable', 'numeric', 'min:0'],
            'reason'      => ['nullable', 'string'],
            'party_type'  => ['required', 'in:supplier,customer'],
            'party_id'    => ['required', 'integer'],
            'brand_id'    => ['sometimes', 'exists:brands,id'],
            'product_id'  => ['sometimes', 'exists:products,id'],
        ];
    }
}
