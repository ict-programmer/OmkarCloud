<?php

namespace App\Http\Requests\OmkarCloud;

use Illuminate\Foundation\Http\FormRequest;

class DetailedResultViewRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'task_id'     => 'required|string|max:128',
            'include_raw' => 'nullable|boolean',
            'format'      => 'nullable|string|in:json,csv,excel',
        ];
    }
}
