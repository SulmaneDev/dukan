<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateExpenseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'category_id'    => ['required', 'exists:expense_categories,id'],
            'date'           => ['required', 'date'],
            'amount'         => ['required', 'numeric', 'min:0'],
            'payment_method' => ['required', 'string', 'max:50'],
            'reference'      => ['nullable', 'string', 'max:255'],
            'description'    => ['nullable', 'string'],
            'media'          => ['nullable', 'file', 'image', 'max:2048'], 

        ];
    }
}
