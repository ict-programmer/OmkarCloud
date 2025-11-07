<?php

namespace App\Http\Requests\Captions;

use App\Enums\Captions\Creator\ResolutionEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AiCreatorSubmitRequest extends FormRequest
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
            'creatorName' => 'nullable|string',
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
