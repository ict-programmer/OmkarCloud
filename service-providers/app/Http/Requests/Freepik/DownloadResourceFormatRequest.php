<?php

namespace App\Http\Requests\Freepik;

use App\Enums\Freepik\ResourceFormatEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

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
            'format' => ['required', 'string', new Enum(ResourceFormatEnum::class)],
        ];
    }
}
