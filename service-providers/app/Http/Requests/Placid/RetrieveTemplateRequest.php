<?php

namespace App\Http\Requests\Placid;

use Illuminate\Foundation\Http\FormRequest;

class RetrieveTemplateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'template_uuid' => 'required|string',
        ];
    }
}
