<?php

namespace App\Http\Requests\OmkarCloud;

use Illuminate\Foundation\Http\FormRequest;

class ScrapeReviewsRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'business_id' => 'required|string|max:1024',
            'max_results' => 'nullable|integer|min:1',
            'language'    => 'nullable|string|size:2',
            'format'      => 'nullable|string|in:json,csv,excel',
        ];
    }
}
