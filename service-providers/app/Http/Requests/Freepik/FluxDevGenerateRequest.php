<?php

namespace App\Http\Requests\Freepik;

use App\Enums\Freepik\Image\FluxDev\AspectRatioEnum;
use App\Enums\Freepik\Image\FluxDev\ColorEffectEnum;
use App\Enums\Freepik\Image\FluxDev\FramingEffectEnum;
use App\Enums\Freepik\Image\FluxDev\LightningEffectEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FluxDevGenerateRequest extends FormRequest
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
            'prompt' => ['required', 'string'],
            'aspect_ratio' => ['nullable', Rule::in(AspectRatioEnum::getValuesInArray())],
            'seed' => ['nullable', 'integer', 'min:1', 'max:4294967295'],
            'styling.effects.color' => ['nullable', Rule::in(ColorEffectEnum::getValuesInArray())],
            'styling.effects.framing' => ['nullable', Rule::in(FramingEffectEnum::getValuesInArray())],
            'styling.effects.lightning' => ['nullable', Rule::in(LightningEffectEnum::getValuesInArray())],
            'styling.colors' => ['nullable', 'array', 'min:1', 'max:5'],
            'styling.colors.*.color' => ['required_with:styling.colors', 'string', 'regex:/^#([A-Fa-f0-9]{6})$/'],
            'styling.colors.*.weight' => ['required_with:styling.colors', 'numeric', 'min:0.05', 'max:1'],
        ];
    }

    public function messages(): array
    {
        return [
            'aspect_ratio.in' => __('The selected aspect ratio is invalid. Valid values: :values', [
                'values' => AspectRatioEnum::getValuesInString(),
            ]),
            'styling.effects.color.in' => __('The selected color effect is invalid. Valid values: :values', [
                'values' => ColorEffectEnum::getValuesInString(),
            ]),
            'styling.effects.framing.in' => __('The selected framing effect is invalid. Valid values: :values', [
                'values' => FramingEffectEnum::getValuesInString(),
            ]),
            'styling.effects.lightning.in' => __('The selected lightning effect is invalid. Valid values: :values', [
                'values' => LightningEffectEnum::getValuesInString(),
            ]),

        ];
    }
}
