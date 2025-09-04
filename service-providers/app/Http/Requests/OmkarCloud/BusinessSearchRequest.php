<?php

namespace App\Http\Requests\OmkarCloud;

use Illuminate\Foundation\Http\FormRequest;

class BusinessSearchRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'query'   => ['required','string','max:512'],
            'filters' => ['sometimes','array'],
            'filters.city' => ['sometimes','string','max:100'],
            'filters.country' => ['sometimes','string','max:100'],
            'filters.rating' => ['sometimes','numeric','min:0','max:5'],
            'format'  => ['sometimes','in:json,csv,excel'],
        ];
    }
}
