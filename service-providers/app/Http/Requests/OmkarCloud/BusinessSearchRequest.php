<?php

namespace App\Http\Requests\OmkarCloud;

use Illuminate\Foundation\Http\FormRequest;

class BusinessSearchRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'query'       => 'required|string',
            'location'    => 'nullable|string',
            'radius_km'   => 'nullable|integer|min:0',
            'max_results' => 'nullable|integer|min:1',
            'format'      => 'nullable|string|in:json,csv,excel',
            'language'    => 'nullable|string|size:2',
        ];
    }
}
