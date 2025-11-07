<?php

namespace App\Http\Requests\Freepik;

use App\Enums\Freepik\ResourceFormatEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DownloadResourceFormatRequest extends FormRequest
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
            'resource_id' => ['required', 'string'],
            'format' => ['required', 'string', Rule::in(ResourceFormatEnum::getValuesInArray())],
        ];
    }

    public function messages(): array
    {
        return [
            'format.in' => __('The selected format is invalid. Valid values: :values', [
                'values' => ResourceFormatEnum::getValuesInString(),
            ]),
        ];
    }
}
