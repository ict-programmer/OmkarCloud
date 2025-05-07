<?php

namespace App\Http\Requests\Placid;

use Illuminate\Foundation\Http\FormRequest;

class RetrievePdfRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'pdf_id' => 'required|integer',
        ];
    }
}
