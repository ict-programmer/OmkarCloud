<?php

namespace App\Http\Requests\Freepik;

use App\Rules\ValidIpfsCid;
use Illuminate\Foundation\Http\FormRequest;

class ImageExpandFluxProRequest extends FormRequest
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
            'image_cid' => ['required', 'string', new ValidIpfsCid],
            'prompt' => ['nullable', 'string'],
            'left' => ['nullable', 'integer', 'min:0', 'max:2048'],
            'right' => ['nullable', 'integer', 'min:0', 'max:2048'],
            'top' => ['nullable', 'integer', 'min:0', 'max:2048'],
            'bottom' => ['nullable', 'integer', 'min:0', 'max:2048'],
        ];
    }
}
