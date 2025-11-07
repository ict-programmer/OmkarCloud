<?php

namespace App\Http\Requests\OmkarCloud;

use Illuminate\Foundation\Http\FormRequest;

class SortByAdsReviewsWebsiteRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'task_id' => 'required|string|max:128',
            'mode'    => 'nullable|string|in:best_customer',
            'format'  => 'nullable|string|in:json,csv,excel',
        ];
    }
}
