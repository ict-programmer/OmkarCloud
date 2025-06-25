<?php

namespace App\Http\Requests\Shutterstock;

use Illuminate\Foundation\Http\FormRequest;

class SearchImagesRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'query' => 'required|string|max:255',
            'orientation' => 'required|string|in:horizontal,vertical,square',
        ];
    }
} 