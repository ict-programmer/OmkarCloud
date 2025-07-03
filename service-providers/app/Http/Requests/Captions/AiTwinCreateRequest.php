<?php

namespace App\Http\Requests\Captions;

use App\Rules\ValidIpfsCid;
use Illuminate\Foundation\Http\FormRequest;

class AiTwinCreateRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'videoCid' => ['required', new ValidIpfsCid],
            'calibrationImageCids' => 'required|array|min:1',
            'calibrationImageCids.*' => ['required', new ValidIpfsCid],
            'language' => 'nullable|string|max:50',
        ];
    }
}
