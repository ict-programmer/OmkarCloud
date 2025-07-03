<?php

namespace App\Http\Requests\Freepik;

use App\Rules\ValidIpfsCid;
use Illuminate\Foundation\Http\FormRequest;

class AiImageClassifierRequest extends FormRequest
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
            'image_cid' => ['required', new ValidIpfsCid],
        ];
    }
}
