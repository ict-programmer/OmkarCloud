<?php

namespace App\Http\Requests\Freepik;

use App\Enums\Freepik\Image\ClassicFast\StylingEffectColorEnum;
use App\Enums\Freepik\Image\ClassicFast\StylingEffectFramingEnum;
use App\Enums\Freepik\Image\ClassicFast\StylingEffectLightningEnum;
use App\Enums\Freepik\Image\ClassicFast\StylingStyleEnum;
use App\Enums\Freepik\Image\Imagen3\AspectRatioEnum;
use App\Enums\Freepik\Image\Imagen3\PersonGenerationEnum;
use App\Enums\Freepik\Image\Imagen3\SafetySettingsEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class Imagen3GenerateRequest extends FormRequest
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

            'webhook_url' => ['nullable', 'url'],

            'num_images' => ['nullable', 'integer', 'between:1,4'],

            'aspect_ratio' => ['nullable', 'string', Rule::in(AspectRatioEnum::getValuesInArray())],

            'styling' => ['nullable', 'array'],

            'styling.style' => ['nullable', 'string', Rule::in(StylingStyleEnum::getValuesInArray())],

            'styling.effects' => ['nullable', 'array'],

            'styling.effects.color' => ['nullable', 'string', Rule::in(StylingEffectColorEnum::getValuesInArray())],

            'styling.effects.lightning' => ['nullable', 'string', Rule::in(StylingEffectLightningEnum::getValuesInArray())],

            'styling.effects.framing' => ['nullable', 'string', Rule::in(StylingEffectFramingEnum::getValuesInArray())],

            'styling.colors' => ['nullable', 'array', 'min:1', 'max:5'],

            'styling.colors.*.color' => ['required_with:styling.colors', 'string', 'regex:/^#([A-Fa-f0-9]{6})$/'],

            'styling.colors.*.weight' => ['required_with:styling.colors', 'numeric', 'gte:0.05', 'lte:1'],

            'person_generation' => ['nullable', 'string', Rule::in(PersonGenerationEnum::getValuesInArray())],

            'safety_settings' => ['nullable', 'string', Rule::in(SafetySettingsEnum::getValuesInArray())],
        ];
    }

    public function messages(): array
    {
        return [
            'aspect_ratio.in' => __('The selected aspect ratio is invalid. Valid values: :values', [
                'values' => AspectRatioEnum::getValuesInString(),
            ]),
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
            'person_generation.in' => __('The selected person generation is invalid. Valid values: :values', [
                'values' => PersonGenerationEnum::getValuesInString(),
            ]),
            'safety_settings.in' => __('The selected safety settings is invalid. Valid values: :values', [
                'values' => SafetySettingsEnum::getValuesInString(),
            ]),
        ];
    }
}
