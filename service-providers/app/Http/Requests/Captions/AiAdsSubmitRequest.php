<?php

namespace App\Http\Requests\Captions;

use App\Enums\Captions\Creator\ResolutionEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AiAdsSubmitRequest extends FormRequest
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
            'script' => 'required|string|max:800',
            'creatorName' => 'required|string',
            'mediaUrls' => 'required|array|min:1|max:10',
            'mediaUrls.*' => 'string|url',
            'resolution' => ['nullable', 'string', Rule::in(ResolutionEnum::getValuesInArray())],
        ];
    }

    public function messages(): array
    {
        return [
            'resolution.in' => __('The selected resolution is invalid. Valid values: :values', [
                'values' => ResolutionEnum::getValuesInString(),
            ]),
        ];
    }
}
