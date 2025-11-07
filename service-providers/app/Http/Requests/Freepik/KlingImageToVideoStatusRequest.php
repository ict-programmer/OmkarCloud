<?php

namespace App\Http\Requests\Freepik;

use App\Enums\Freepik\KlingModelEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class KlingImageToVideoStatusRequest extends FormRequest
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
            'task_id' => ['required', 'string'],
            'model' => ['required', Rule::in(KlingModelEnum::getValuesInArray())],
        ];
    }

    public function messages()
    {
        return [
            'model.in' => __('The selected model is invalid. Valid values: :values', ['values' => KlingModelEnum::getValuesInString()]),
        ];
    }
}
