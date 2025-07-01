<?php

namespace App\Http\Requests\Captions;

use Illuminate\Foundation\Http\FormRequest;

class AiTwinCreateRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'videoUrl' => 'required|url',
            'calibrationImageUrls' => 'required|array|min:1',
            'calibrationImageUrls.*' => 'required|string|url',
            'language' => 'nullable|string|max:50',
        ];
    }
}
