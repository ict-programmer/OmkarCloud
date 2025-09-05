<?php

namespace App\Http\Requests\OmkarCloud;

use Illuminate\Foundation\Http\FormRequest;

class ExportToJsonCsvExcelRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'action'  => 'required|string|in:start,abort,delete',
            'task_id' => 'required_if:action,abort,delete|nullable|string|max:128',

            'query'   => 'required_if:action,start|nullable|string',
            'urls'    => 'required_without:query|array|min:1|max:100',
            'urls.*'  => 'url',

            'format'  => 'nullable|string|in:json,csv,excel',
        ];
    }
}
