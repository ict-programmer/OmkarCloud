<?php

namespace App\Http\Requests\OmkarCloud;

use Illuminate\Foundation\Http\FormRequest;

class SearchByLinksRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'urls'   => 'required|array|min:1|max:100',
            'urls.*' => 'url',
            'format' => 'nullable|string|in:json,csv,excel',
        ];
    }
}
