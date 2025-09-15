<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateExpenseCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                function ($attr, $value, $fail) {
                    if ($this->user()->expenseCategory()->where('name', $value)->where('id', '!=', $this->route('id'))->exists()) {
                        $fail("Expense category '{$value}' already exists.");
                    }
                },
            ],
            'description' => ['nullable', 'string'],
        ];
    }
}
