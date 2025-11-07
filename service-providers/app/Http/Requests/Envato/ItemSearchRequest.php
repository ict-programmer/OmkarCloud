<?php

namespace App\Http\Requests\Envato;

use Illuminate\Foundation\Http\FormRequest;

class ItemSearchRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'site' => 'required|string|max:255',
            'term' => 'required|string|max:255',
        ];
    }
} 