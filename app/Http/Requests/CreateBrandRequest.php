<?php

namespace App\Http\Requests;

use App\Models\Brand;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CreateBrandRequest extends FormRequest
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
            "name" => [
                "string",
                "required",
                function ($attr, $value, $fail) {
                    if ($this->user()
                        ->brand()
                        ->where('name', $value)
                        ->exists()
                    ) {
                        $fail("Brand with name {$value} already exists.");
                    }
                },
            ]
        ];
    }
}
