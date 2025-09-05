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
            'page' => ['nullable', 'integer', 'min:1', 'max:100'],
            'limit' => ['required', 'integer', 'min:1', 'max:100'],
            'order' => ['required', 'string', 'in:relevance,recent'],
            'term' => ['nullable', 'string'],
            'slug' => ['nullable', 'string'],

            // Orientation filters
            'filters.orientation.landscape' => ['nullable', 'in:0,1'],
            'filters.orientation.portrait' => ['nullable', 'in:0,1'],
            'filters.orientation.square' => ['nullable', 'in:0,1'],
            'filters.orientation.panoramic' => ['nullable', 'in:0,1'],

            // Content type filters
            'filters.content_type.photo' => ['nullable', 'in:0,1'],
            'filters.content_type.psd' => ['nullable', 'in:0,1'],
            'filters.content_type.vector' => ['nullable', 'in:0,1'],

            // License filters
            'filters.license.freemium' => ['nullable', 'in:0,1'],
            'filters.license.premium' => ['nullable', 'in:0,1'],

            // People filters
            'filters.people.include' => ['nullable', 'in:0,1'],
            'filters.people.exclude' => ['nullable', 'in:0,1'],
            'filters.people.number' => ['nullable', 'string', Rule::in(PeopleNumberEnum::getValuesInArray())],
            'filters.people.age' => ['nullable', 'string', Rule::in(PeopleAgeEnum::getValuesInArray())],
            'filters.people.gender' => ['nullable', 'string', Rule::in(PeopleGenderEnum::getValuesInArray())],
            'filters.people.ethnicity' => ['nullable', 'string', Rule::in(PeopleEthnicityEnum::getValuesInArray())],

            'filters.period' => ['nullable', 'string', Rule::in(PeriodEnum::getValuesInArray())],
            'filters.color' => ['nullable', 'string', Rule::in(ColorEnum::getValuesInArray())],
            'filters.author' => ['nullable', 'integer', 'min:1'],

            // AI-generated flags
            'filters.ai-generated.excluded' => ['nullable', 'in:0,1'],
            'filters.ai-generated.only' => ['nullable', 'in:0,1'],

            // Vector & PSD
            'filters.vector.type' => ['nullable', 'string', Rule::in(VectorTypeEnum::getValuesInArray())],
            'filters.vector.style' => ['nullable', 'string', Rule::in(VectorStyleEnum::getValuesInArray())],
            'filters.psd.type' => ['nullable', 'string', Rule::in(PsdTypeEnum::getValuesInArray())],

            // ID filter
            'filters.ids' => ['nullable', 'string'],
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
