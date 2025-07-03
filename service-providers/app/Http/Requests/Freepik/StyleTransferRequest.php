<?php

namespace App\Http\Requests\Freepik;

use App\Enums\Freepik\ImageEditing\StyleTransfer\EngineEnum;
use App\Enums\Freepik\ImageEditing\StyleTransfer\FlavorEnum;
use App\Enums\Freepik\ImageEditing\StyleTransfer\PortraitBeautifierEnum;
use App\Enums\Freepik\ImageEditing\StyleTransfer\PortraitStyleEnum;
use App\Rules\ValidIpfsCid;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StyleTransferRequest extends FormRequest
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
            'image_cid' => ['required', 'string', new ValidIpfsCid],
            'reference_image_cid' => ['required', 'string', new ValidIpfsCid],
            'prompt' => ['nullable', 'string'],
            'style_strength' => ['nullable', 'integer', 'between:0,100'],
            'structure_strength' => ['nullable', 'integer', 'between:0,100'],
            'is_portrait' => ['nullable', 'boolean'],
            'portrait_style' => ['nullable', 'string', Rule::in(PortraitStyleEnum::getValuesInArray())],
            'portrait_beautifier' => ['nullable', 'string', Rule::in(PortraitBeautifierEnum::getValuesInArray())],
            'flavor' => ['nullable', 'string', Rule::in(FlavorEnum::getValuesInArray())],
            'engine' => ['nullable', 'string', Rule::in(EngineEnum::getValuesInArray())],
            'fixed_generation' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'portrait_style.in' => __('The selected portrait style is invalid. Valid values: :values', [
                'values' => PortraitStyleEnum::getValuesInString(),
            ]),
            'portrait_beautifier.in' => __('The selected portrait beautifier is invalid. Valid values: :values', [
                'values' => PortraitBeautifierEnum::getValuesInString(),
            ]),
            'flavor.in' => __('The selected flavor is invalid. Valid values: :values', [
                'values' => FlavorEnum::getValuesInString(),
            ]),
            'engine.in' => __('The selected engine is invalid. Valid values: :values', [
                'values' => EngineEnum::getValuesInString(),
            ]),

        ];
    }
}
