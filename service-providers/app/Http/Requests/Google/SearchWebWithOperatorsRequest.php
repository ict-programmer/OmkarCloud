<?php

namespace App\Http\Requests\Google;

use App\Enums\Google\C2CoffEnum;
use App\Enums\Google\FilterEnum;
use App\Enums\Google\SafeEnum;
use App\Enums\Google\SiteSearchFilterEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SearchWebWithOperatorsRequest extends FormRequest
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
            'q' => ['required', 'string', 'max:255'],

            'c2coff' => ['nullable', Rule::in(C2CoffEnum::getValuesInArray())],
            'cr' => ['nullable', 'string', 'max:50'],
            'dateRestrict' => ['nullable', 'string', 'max:10'],
            'exactTerms' => ['nullable', 'string', 'max:255'],
            'excludeTerms' => ['nullable', 'string', 'max:255'],
            'fileType' => ['nullable', 'string', 'max:20'],
            'filter' => ['nullable', Rule::in(FilterEnum::getValuesInArray())],
            'gl' => ['nullable', 'string', 'size:2'],
            'googlehost' => ['nullable', 'string', 'max:50'],
            'highRange' => ['nullable', 'string', 'max:50'],
            'hl' => ['nullable', 'string', 'max:5'],
            'hq' => ['nullable', 'string', 'max:255'],

            'linkSite' => ['nullable', 'string', 'max:255'],
            'lowRange' => ['nullable', 'string', 'max:50'],
            'lr' => ['nullable', 'string', 'max:20'],
            'num' => ['nullable', 'integer', 'min:1', 'max:10'],
            'orTerms' => ['nullable', 'string', 'max:255'],

            'rights' => ['nullable', 'string', 'max:100'],
            'safe' => ['nullable', Rule::in(SafeEnum::getValuesInArray())],
            'siteSearch' => ['nullable', 'string', 'max:255'],
            'siteSearchFilter' => ['nullable', Rule::in(SiteSearchFilterEnum::getValuesInArray())],

            'sort' => ['nullable', 'string', 'max:50'],
            'start' => ['nullable', 'integer', 'min:1', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'c2coff.in' => __('The selected c2coff value is invalid. Valid values: :values', [
                'values' => C2CoffEnum::getValuesInString(),
            ]),
            'filter.in' => __('The selected filter value is invalid. Valid values: :values', [
                'values' => FilterEnum::getValuesInString(),
            ]),
            'safe.in' => __('The selected safe value is invalid. Valid values: :values', [
                'values' => SafeEnum::getValuesInString(),
            ]),
            'siteSearchFilter.in' => __('The selected siteSearchFilter is invalid. Valid values: :values', [
                'values' => SiteSearchFilterEnum::getValuesInString(),
            ]),
        ];
    }
}
