<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CreateProductRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255', function ($attr,$val,$fail) {
                if($this->user()->product()->where('name',$val)->exists()) {
                    $fail("Product '{$val}' already exists.");
                };
            }],
            'price' => ['required', 'integer', 'min:0'],
            'brand_id' => ['required', 'exists:brands,id'],
            'image' => ['required', 'file', 'image', 'max:2048'],
        ];
    }
}
