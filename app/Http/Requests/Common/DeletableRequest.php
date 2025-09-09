<?php

namespace App\Http\Requests\Common;

use Illuminate\Foundation\Http\FormRequest;

class DeletableRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Set to true if user is allowed to delete resources
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'deletable_ids'   => ['required', 'array'], 
            'deletable_ids.*' => ['required', 'integer'], 
        ];
    }

    /**
     * Custom messages for validation.
     */
    public function messages(): array
    {
        return [
            'deletable_ids.required'   => 'No resources selected for deletion.',
            'deletable_ids.array'      => 'Invalid data format.',
            'deletable_ids.*.integer'  => 'Invalid resource ID.',
        ];
    }
}
