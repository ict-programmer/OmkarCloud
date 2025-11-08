<?php

namespace App\Http\Requests\Freepik;

use App\Enums\Freepik\KlingElementModelEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class KlingElementsVideoStatusRequest extends FormRequest
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
            'model' => ['required', Rule::in(KlingElementModelEnum::getValuesInArray())],
        ];
    }

    public function messages()
    {
        return [
            'model.in' => __('The selected model is invalid. Valid values: :values', ['values' => KlingElementModelEnum::getValuesInString()]),
        ];
    }
}
