<?php

namespace App\Http\Requests\Envato;

use Illuminate\Foundation\Http\FormRequest;

class UserAccountDetailsRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'username' => 'required|string|max:255',
        ];
    }
} 