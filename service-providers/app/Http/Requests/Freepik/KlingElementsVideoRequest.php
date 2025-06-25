<?php

namespace App\Http\Requests\Freepik;

use App\Enums\Freepik\AspectRatioEnum;
use App\Enums\Freepik\KlingElementModelEnum;
use App\Enums\Freepik\KlingVideoDurationEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class KlingElementsVideoRequest extends FormRequest
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
            'model' => ['required', Rule::in(KlingElementModelEnum::getValuesInArray())],
            'images' => ['required', 'array', 'max:4'],
            'images.*' => ['required', 'url'],
            'prompt' => ['nullable', 'string', 'max:2500'],
            'negative_prompt' => ['nullable', 'string', 'max:2500'],
            'duration' => ['required', Rule::in(KlingVideoDurationEnum::getValuesInArray())],
            'aspect_ratio' => ['nullable', Rule::in(AspectRatioEnum::getValuesInArray())],

        ];
    }

    public function messages()
    {
        return [
            'model.in' => __('The selected model is invalid. Valid values: :values', ['values' => KlingElementModelEnum::getValuesInString()]),
            'aspect_ratio.in' => __('The selected aspect_ratio is invalid. Valid values: :values', ['values' => AspectRatioEnum::getValuesInString()]),
            'duration.in' => __('The selected duration is invalid. Valid values: :values', ['values' => KlingVideoDurationEnum::getValuesInString()]),
        ];
    }
}
