<?php

namespace App\Http\Requests\Freepik;

use App\Enums\Freepik\Image\Mystic\LoraQualityEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LoraStyleTrainRequest extends FormRequest
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
            'name' => ['required', 'string'],
            'quality' => ['required', Rule::in(LoraQualityEnum::getValuesInArray())],
            'images' => ['required', 'array', 'min:6', 'max:20'],
            'images.*' => ['required', 'url'],
            'description' => ['nullable', 'string'],
        ];
    }

    public function messages()
    {
        return [
            'quality.in' => __('The selected quality is invalid. Valid values: :values', ['values' => LoraQualityEnum::getValuesInString()]),
        ];
    }
}
