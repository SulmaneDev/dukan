<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CreateSupplierReceiptRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'supplier_id'    => ['required', 'exists:suppliers,id'],
            'date'           => ['required', 'date'],
            'amount'         => ['required', 'numeric', 'min:0'],
            'payment_method' => ['required', 'string', 'max:50'],
            'description'    => ['nullable', 'string'],
        ];
    }
}
