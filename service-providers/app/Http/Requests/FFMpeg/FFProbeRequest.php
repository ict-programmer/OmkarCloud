<?php

namespace App\Http\Requests\FFMpeg;

use Illuminate\Foundation\Http\FormRequest;

class FFProbeRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'file_link' => 'required|url',
            'output_format' => 'nullable|string|in:json,xml,csv,flat,ini,default',
            'show_format' => 'nullable|boolean',
            'show_streams' => 'nullable|boolean',
            'show_chapters' => 'nullable|boolean',
            'show_programs' => 'nullable|boolean',
            'select_streams' => 'nullable|string',
        ];
    }
}
