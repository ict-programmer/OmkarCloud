<?php

namespace App\Http\Requests\Envato;

use Illuminate\Foundation\Http\FormRequest;

class ItemDetailsRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'item_id' => 'required|string|max:255',
        ];
    }
} 