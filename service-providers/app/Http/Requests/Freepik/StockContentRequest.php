<?php

namespace App\Http\Requests\Freepik;

use App\Enums\Freepik\ColorEnum;
use App\Enums\Freepik\PeopleAgeEnum;
use App\Enums\Freepik\PeopleEthnicityEnum;
use App\Enums\Freepik\PeopleGenderEnum;
use App\Enums\Freepik\PeopleNumberEnum;
use App\Enums\Freepik\PeriodEnum;
use App\Enums\Freepik\PsdTypeEnum;
use App\Enums\Freepik\VectorStyleEnum;
use App\Enums\Freepik\VectorTypeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StockContentRequest extends FormRequest
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
            'page' => ['sometimes', 'integer', 'min:1', 'max:100'],
            'limit' => ['required', 'integer', 'min:1', 'max:100'],
            'order' => ['required', 'string', 'in:relevance,recent'],
            'term' => ['sometimes', 'string'],
            'slug' => ['sometimes', 'string'],

            // Orientation filters
            'filters.orientation.landscape' => ['sometimes', 'in:0,1'],
            'filters.orientation.portrait' => ['sometimes', 'in:0,1'],
            'filters.orientation.square' => ['sometimes', 'in:0,1'],
            'filters.orientation.panoramic' => ['sometimes', 'in:0,1'],

            // Content type filters
            'filters.content_type.photo' => ['sometimes', 'in:0,1'],
            'filters.content_type.psd' => ['sometimes', 'in:0,1'],
            'filters.content_type.vector' => ['sometimes', 'in:0,1'],

            // License filters
            'filters.license.freemium' => ['sometimes', 'in:0,1'],
            'filters.license.premium' => ['sometimes', 'in:0,1'],

            // People filters
            'filters.people.include' => ['sometimes', 'in:0,1'],
            'filters.people.exclude' => ['sometimes', 'in:0,1'],
            'filters.people.number' => ['sometimes', 'string', Rule::in(PeopleNumberEnum::getValuesInArray())],
            'filters.people.age' => ['sometimes', 'string', Rule::in(PeopleAgeEnum::getValuesInArray())],
            'filters.people.gender' => ['sometimes', 'string', Rule::in(PeopleGenderEnum::getValuesInArray())],
            'filters.people.ethnicity' => ['sometimes', 'string', Rule::in(PeopleEthnicityEnum::getValuesInArray())],

            'filters.period' => ['sometimes', 'string', Rule::in(PeriodEnum::getValuesInArray())],
            'filters.color' => ['sometimes', 'string', Rule::in(ColorEnum::getValuesInArray())],
            'filters.author' => ['sometimes', 'integer', 'min:1'],

            // AI-generated flags
            'filters.ai-generated.excluded' => ['sometimes', 'in:0,1'],
            'filters.ai-generated.only' => ['sometimes', 'in:0,1'],

            // Vector & PSD
            'filters.vector.type' => ['sometimes', 'string', Rule::in(VectorTypeEnum::getValuesInArray())],
            'filters.vector.style' => ['sometimes', 'string', Rule::in(VectorStyleEnum::getValuesInArray())],
            'filters.psd.type' => ['sometimes', 'string', Rule::in(PsdTypeEnum::getValuesInArray())],

            // ID filter
            'filters.ids' => ['sometimes', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'order.in' => __('The selected order is invalid. Valid values: :values', [
                'values' => 'relevance,recent',
            ]),
            'filters.people.number.in' => __('The selected people number is invalid. Valid values: :values', [
                'values' => PeopleNumberEnum::getValuesInString(),
            ]),
            'filters.people.age.in' => __('The selected people age is invalid. Valid values: :values', [
                'values' => PeopleAgeEnum::getValuesInString(),
            ]),
            'filters.people.gender.in' => __('The selected people gender is invalid. Valid values: :values', [
                'values' => PeopleGenderEnum::getValuesInString(),
            ]),
            'filters.people.ethnicity.in' => __('The selected people ethnicity is invalid. Valid values: :values', [
                'values' => PeopleEthnicityEnum::getValuesInString(),
            ]),
            'filters.period.in' => __('The selected period is invalid. Valid values: :values', [
                'values' => PeriodEnum::getValuesInString(),
            ]),
            'filters.color.in' => __('The selected color is invalid. Valid values: :values', [
                'values' => ColorEnum::getValuesInString(),
            ]),
            'filters.vector.type.in' => __('The selected vector type is invalid. Valid values: :values', [
                'values' => VectorTypeEnum::getValuesInString(),
            ]),
            'filters.vector.style.in' => __('The selected vector style is invalid. Valid values: :values', [
                'values' => VectorStyleEnum::getValuesInString(),
            ]),
            'filters.psd.type.in' => __('The selected PSD type is invalid. Valid values: :values', [
                'values' => PsdTypeEnum::getValuesInString(),
            ]),
        ];
    }
}
