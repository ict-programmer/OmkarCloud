<?php

namespace App\Http\Requests\Freepik;

use App\Enums\Freepik\ImageEditing\Relight\RelightEngineEnum;
use App\Enums\Freepik\ImageEditing\Relight\RelightStyleEnum;
use App\Enums\Freepik\ImageEditing\Relight\TransferLightAEnum;
use App\Enums\Freepik\ImageEditing\Relight\TransferLightBEnum;
use App\Rules\ValidIpfsCid;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RelightImageRequest extends FormRequest
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
            'prompt' => ['nullable', 'string'],
            'transfer_light_from_reference_image_cid' => ['nullable', 'string', new ValidIpfsCid],
            'transfer_light_from_lightmap_cid' => ['nullable', 'string', new ValidIpfsCid],
            'light_transfer_strength' => ['nullable', 'integer', 'between:0,100'],
            'interpolate_from_original' => ['nullable', 'boolean'],
            'change_background' => ['nullable', 'boolean'],
            'style' => ['nullable', Rule::in(RelightStyleEnum::getValuesInArray())],
            'preserve_details' => ['nullable', 'boolean'],

            'advanced_settings.whites' => ['nullable', 'integer', 'between:0,100'],
            'advanced_settings.blacks' => ['nullable', 'integer', 'between:0,100'],
            'advanced_settings.brightness' => ['nullable', 'integer', 'between:0,100'],
            'advanced_settings.contrast' => ['nullable', 'integer', 'between:0,100'],
            'advanced_settings.saturation' => ['nullable', 'integer', 'between:0,100'],
            'advanced_settings.engine' => ['nullable', Rule::in(RelightEngineEnum::getValuesInArray())],
            'advanced_settings.transfer_light_a' => ['nullable', Rule::in(TransferLightAEnum::getValuesInArray())],
            'advanced_settings.transfer_light_b' => ['nullable', Rule::in(TransferLightBEnum::getValuesInArray())],
            'advanced_settings.fixed_generation' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'style.in' => __('The selected style is invalid. Valid values: :values', [
                'values' => RelightStyleEnum::getValuesInString(),
            ]),
            'advanced_settings.engine.in' => __('The selected advanced settings engine is invalid. Valid values: :values', [
                'values' => RelightEngineEnum::getValuesInString(),
            ]),
            'advanced_settings.transfer_light_a.in' => __('The selected advanced settings transfer light a is invalid. Valid values: :values', [
                'values' => TransferLightAEnum::getValuesInString(),
            ]),
            'advanced_settings.transfer_light_b.in' => __('The selected advanced settings transfer light a is invalid. Valid values: :values', [
                'values' => TransferLightBEnum::getValuesInString(),
            ]),
        ];
    }
}
