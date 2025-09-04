<?php

namespace App\Http\Requests\OmkarCloud;

use Illuminate\Foundation\Http\FormRequest;

class FilterResultsRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'task_id' => ['sometimes','string','max:128'],
            'filters' => ['required','array'],
            'format'  => ['sometimes','in:json,csv,excel'],
        ];
    }
}
