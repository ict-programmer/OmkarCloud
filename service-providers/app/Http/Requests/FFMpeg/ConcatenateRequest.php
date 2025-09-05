<?php

namespace App\Http\Requests\FFMpeg;

use Illuminate\Foundation\Http\FormRequest;

class ConcatenateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'input_files' => 'required|array|min:2|max:20',
            'input_files.*' => 'required|url',
        ];
    }

    public function messages(): array
    {
        return [
            'input_files.min' => 'At least 2 input files are required for concatenation.',
            'input_files.max' => 'Maximum of 20 input files allowed for concatenation.',
            'input_files.*.url' => 'Each input file must be a valid URL.',
        ];
    }
}
