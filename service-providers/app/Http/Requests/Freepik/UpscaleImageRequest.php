<?php

namespace App\Http\Requests\Freepik;

use App\Enums\Freepik\ImageEditing\Upscaler\OptimizedForEnum;
use App\Enums\Freepik\ImageEditing\Upscaler\ScaleFactorEnum;
use App\Enums\Freepik\ImageEditing\Upscaler\UpscaleEngineEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpscaleImageRequest extends FormRequest
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
            'image' => ['required', 'string', 'url'],
            'scale_factor' => ['nullable', Rule::in(ScaleFactorEnum::getValuesInArray())],
            'optimized_for' => ['nullable', Rule::in(OptimizedForEnum::getValuesInArray())],
            'prompt' => ['nullable', 'string'],
            'creativity' => ['nullable', 'integer', 'between:-10,10'],
            'hdr' => ['nullable', 'integer', 'between:-10,10'],
            'resemblance' => ['nullable', 'integer', 'between:-10,10'],
            'fractality' => ['nullable', 'integer', 'between:-10,10'],
            'engine' => ['nullable',  Rule::in(UpscaleEngineEnum::getValuesInArray())],
        ];
    }

    public function messages(): array
    {
        return [
            'scale_factor.in' => __('The selected scale factor is invalid. Valid values: :values', [
                'values' => ScaleFactorEnum::getValuesInString(),
            ]),
            'optimized_for.in' => __('The selected optimized for is invalid. Valid values: :values', [
                'values' => OptimizedForEnum::getValuesInString(),
            ]),
            'engine.in' => __('The selected engine is invalid. Valid values: :values', [
                'values' => UpscaleEngineEnum::getValuesInString(),
            ]),

        ];
    }
}
