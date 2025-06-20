<?php

namespace App\Http\Requests\Shutterstock;

use Illuminate\Foundation\Http\FormRequest;

class GetVideoRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'video_id' => 'required|string|regex:/^[1-9][0-9]*$/',
        ];
    }
} 