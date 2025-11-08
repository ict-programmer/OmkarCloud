<?php

namespace App\Http\Requests\Captions;

use App\Rules\ValidIpfsCid;
use Illuminate\Foundation\Http\FormRequest;

class AiTranslateSubmitRequest extends FormRequest
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
            'videoCid' => ['required', 'string', new ValidIpfsCid],
            'sourceLanguage' => 'required|string',
            'targetLanguage' => 'required|string',
        ];
    }
}
