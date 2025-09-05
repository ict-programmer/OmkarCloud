<?php

namespace App\Http\Requests\Envato;

use Illuminate\Foundation\Http\FormRequest;

class CategoriesBySiteRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'site' => ['required', 'string'],
        ];
    }
} 