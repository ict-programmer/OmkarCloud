<?php

namespace App\Http\Requests\Freepik;

use App\Enums\Freepik\Image\Mystic\LoraCharacterQualityEnum;
use App\Enums\Freepik\Image\Mystic\LoraGenderEnum;
use App\Rules\ValidIpfsCid;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LoraCharacterTrainRequest extends FormRequest
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
            'quality' => ['required', Rule::in(LoraCharacterQualityEnum::getValuesInArray())],
            'gender' => ['required', Rule::in(LoraGenderEnum::getValuesInArray())],
            'image_cids' => ['required', 'array', 'min:8', 'max:20'],
            'image_cids.*' => ['required', new ValidIpfsCid],
            'description' => ['nullable', 'string'],

        ];
    }

    public function messages()
    {
        return [
            'quality.in' => __('The selected quality is invalid. Valid values: :values', ['values' => LoraCharacterQualityEnum::getValuesInString()]),
            'gender.in' => __('The selected gender is invalid. Valid values: :values', ['values' => LoraGenderEnum::getValuesInString()]),
        ];
    }
}
