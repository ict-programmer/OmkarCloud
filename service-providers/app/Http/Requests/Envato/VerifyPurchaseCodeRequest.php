<?php

namespace App\Http\Requests\Envato;

use Illuminate\Foundation\Http\FormRequest;

class VerifyPurchaseCodeRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'purchase_code' => 'required|string|max:255',
        ];
    }
} 