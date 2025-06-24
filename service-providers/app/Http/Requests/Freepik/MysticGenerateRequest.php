<?php

namespace App\Http\Requests\Freepik;

use App\Enums\Freepik\Image\Mystic\AspectRatioEnum;
use App\Enums\Freepik\Image\Mystic\EngineEnum;
use App\Enums\Freepik\Image\Mystic\ModelEnum;
use App\Enums\Freepik\Image\Mystic\ResolutionEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MysticGenerateRequest extends FormRequest
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
            'prompt' => 'required|string',
            'structure_reference' => 'nullable|string',
            'structure_strength' => 'nullable|integer|min:0|max:100',
            'style_reference' => 'nullable|string',
            'adherence' => 'nullable|integer|min:0|max:100',
            'hdr' => 'nullable|integer|min:0|max:100',
            'resolution' => ['nullable', Rule::in(ResolutionEnum::getValuesInArray())],
            'aspect_ratio' => ['nullable', Rule::in(AspectRatioEnum::getValuesInArray())],
            'model' => ['nullable', Rule::in(ModelEnum::getValuesInArray())],
            'creative_detailing' => 'nullable|integer|min:0|max:100',
            'engine' => ['nullable', Rule::in(EngineEnum::getValuesInArray())],
            'fixed_generation' => 'nullable|boolean',
            'filter_nsfw' => 'nullable|boolean',

            // styling object
            'styling' => 'nullable|array',

            // styling.styles: max 1 object
            'styling.styles' => 'nullable|array|max:1',
            'styling.styles.*.name' => 'required_with:styling.styles|string',
            'styling.styles.*.strength' => 'nullable|numeric|min:0|max:200',

            // styling.characters: max 1 object, structure not strictly defined
            'styling.characters' => 'nullable|array|max:1',
            'styling.characters.*' => 'nullable|array',
            'styling.characters.*.strength' => 'required_with:styling.characters|numeric|min:0|max:200',
            'styling.characters.*.id' => 'nullable|string',

            // styling.colors: 1 to 5 objects, each must have valid hex color code string
            'styling.colors' => 'nullable|array|min:1|max:5',
            'styling.colors.*.color' => [
                'required',
                'string',
                'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/',
            ],
            'styling.colors.*.weight' => 'nullable|numeric|gte:0.05|lte:1',
        ];
    }

    public function messages(): array
    {
        return [
            'resolution.in' => __('The selected resolution is invalid. Valid values: :values', [
                'values' => ResolutionEnum::getValuesInString(),
            ]),
            'aspect_ratio.in' => __('The selected aspect ratio is invalid. Valid values: :values', [
                'values' => AspectRatioEnum::getValuesInString(),
            ]),
            'model.in' => __('The selected model is invalid. Valid values: :values', [
                'values' => ModelEnum::getValuesInString(),
            ]),
            'engine.in' => __('The selected engine is invalid. Valid values: :values', [
                'values' => EngineEnum::getValuesInString(),
            ]),
            'styling.styles.max' => 'Only 1 style is allowed in styling.styles.',
            'styling.styles.*.name.required_with' => 'The style name is required when styling.styles is present.',
            'styling.colors.min' => 'At least 1 color is required in styling.colors.',
            'styling.colors.max' => 'No more than 5 colors are allowed in styling.colors.',
            'styling.colors.*.color.regex' => 'Each color must be a valid hex color code, e.g., #AABBCC or #ABC.',
            'styling.colors.*.color.required' => 'Each color is required.',
            'styling.colors.*.color.string' => 'Each color must be a string.',
            'styling.colors.*.weight.numeric' => 'Color weight must be a numeric value.',
            'styling.colors.*.weight.min' => 'Color weight must be at least 0.',
            'styling.colors.*.weight.max' => 'Color weight cannot be more than 1.',
        ];
    }
}
