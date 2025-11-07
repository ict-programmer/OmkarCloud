<?php

namespace App\Http\Requests\Freepik;

use App\Enums\Freepik\Image\ClassicFast\ImageSizeEnum;
use App\Enums\Freepik\Image\ClassicFast\StylingEffectColorEnum;
use App\Enums\Freepik\Image\ClassicFast\StylingEffectFramingEnum;
use App\Enums\Freepik\Image\ClassicFast\StylingEffectLightningEnum;
use App\Enums\Freepik\Image\ClassicFast\StylingStyleEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ClassicFastGenerateRequest extends FormRequest
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
            'prompt' => ['required', 'string', 'min:3'],
            'negative_prompt' => ['nullable', 'string', 'min:3'],

            'guidance_scale' => ['nullable', 'numeric', 'gte:0', 'lte:2'],

            'num_images' => ['nullable', 'integer', 'gte:1', 'lte:4'],

            'seed' => ['nullable', 'integer', 'gte:0', 'lte:1000000'],

            'filter_nsfw' => ['nullable', 'boolean'],

            // styling object
            'styling' => ['nullable', 'array'],

            // styling.style enum
            'styling.style' => ['nullable', 'string', Rule::in(StylingStyleEnum::getValuesInArray())],

            // styling.colors array: 1-5 elements
            'styling.colors' => ['nullable', 'array', 'min:1', 'max:5'],
            'styling.colors.*.color' => [
                'required',
                'string',
                'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/',
            ],
            'styling.colors.*.weight' => ['nullable', 'numeric', 'gte:0.05', 'lte:1'],

            // styling.effects object
            'styling.effects' => ['nullable', 'array'],

            // styling.effects.color enum
            'styling.effects.color' => ['nullable', 'string', Rule::in(StylingEffectColorEnum::getValuesInArray())],

            // styling.effects.lightning enum
            'styling.effects.lightning' => ['nullable', 'string', Rule::in(StylingEffectLightningEnum::getValuesInArray())],

            // styling.effects.framing enum
            'styling.effects.framing' => ['nullable', 'string', Rule::in(StylingEffectFramingEnum::getValuesInArray())],

            // image object
            'image' => ['nullable', 'array'],

            // image.size enum
            'image.size' => ['nullable', 'string', Rule::in(ImageSizeEnum::getValuesInArray())],
        ];
    }

    public function messages(): array
    {
        return [
            'styling.style.in' => __('The selected styling style is invalid. Valid values: :values', [
                'values' => StylingStyleEnum::getValuesInString(),
            ]),
            'styling.effects.color.in' => __('The selected styling effect color is invalid. Valid values: :values', [
                'values' => StylingEffectColorEnum::getValuesInString(),
            ]),
            'styling.effects.lightning.in' => __('The selected styling effect lightning is invalid. Valid values: :values', [
                'values' => StylingEffectLightningEnum::getValuesInString(),
            ]),
            'styling.effects.framing.in' => __('The selected styling effect framing is invalid. Valid values: :values', [
                'values' => StylingEffectFramingEnum::getValuesInString(),
            ]),
            'image.size.in' => __('The selected image size is invalid. Valid values: :values', [
                'values' => ImageSizeEnum::getValuesInString(),
            ]),

        ];
    }
}
