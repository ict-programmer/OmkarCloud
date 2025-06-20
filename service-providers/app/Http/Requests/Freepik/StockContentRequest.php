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
use Illuminate\Validation\Rules\Enum;

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
            'filters.people.number' => ['sometimes', 'string', new Enum(PeopleNumberEnum::class)],
            'filters.people.age' => ['sometimes', 'string', new Enum(PeopleAgeEnum::class)],
            'filters.people.gender' => ['sometimes', 'string', new Enum(PeopleGenderEnum::class)],
            'filters.people.ethnicity' => ['sometimes', 'string', new Enum(PeopleEthnicityEnum::class)],

            // Period, color, author
            'filters.period' => ['sometimes', 'string', new Enum(PeriodEnum::class)],
            'filters.color' => ['sometimes', 'string', new Enum(ColorEnum::class)],
            'filters.author' => ['sometimes', 'integer', 'min:1'],

            // AI-generated flags
            'filters.ai-generated.excluded' => ['sometimes', 'in:0,1'],
            'filters.ai-generated.only' => ['sometimes', 'in:0,1'],

            // Vector & PSD
            'filters.vector.type' => ['sometimes', 'string', new Enum(VectorTypeEnum::class)],
            'filters.vector.style' => ['sometimes', 'string', new Enum(VectorStyleEnum::class)],
            'filters.psd.type' => ['sometimes', 'string', new Enum(PsdTypeEnum::class)],

            // ID filter
            'filters.ids' => ['sometimes', 'string'],
        ];
    }
}
