<?php

namespace App\Http\Requests\Shutterstock;

use Illuminate\Foundation\Http\FormRequest;

class AddToCollectionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
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
            'collection_id' => 'required|string|regex:/^[1-9][0-9]*$/',
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|string|regex:/^[1-9][0-9]*$/',
            'items.*.media_type' => 'required|string|in:image',
        ];
    }
} 