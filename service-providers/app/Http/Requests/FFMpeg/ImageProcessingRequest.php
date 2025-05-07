<?php

namespace App\Http\Requests\FFMpeg;

use Illuminate\Foundation\Http\FormRequest;

class ImageProcessingRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'input_file' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
            'width' => 'required|integer|min:1',
            'height' => 'required|integer|min:1',
        ];
    }
}
