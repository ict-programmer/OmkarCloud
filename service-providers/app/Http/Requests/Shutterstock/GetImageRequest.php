<?php

namespace App\Http\Requests\Shutterstock;

use Illuminate\Foundation\Http\FormRequest;

class GetImageRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'image_id' => 'required|string|regex:/^[1-9][0-9]*$/',
        ];
    }
} 