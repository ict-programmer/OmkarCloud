<?php

namespace App\Http\Requests\OmkarCloud;

use Illuminate\Foundation\Http\FormRequest;

class SearchLinksRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'links'   => ['required','array','min:1','max:100'],
            'links.*' => ['url'],
            'filters' => ['sometimes','array'],
            'format'  => ['sometimes','in:json,csv,excel'],
        ];
    }
}
