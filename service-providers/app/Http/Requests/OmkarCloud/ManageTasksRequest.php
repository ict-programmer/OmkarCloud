<?php

namespace App\Http\Requests\OmkarCloud;

use Illuminate\Foundation\Http\FormRequest;

class ManageTasksRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'action' => ['required','in:start,abort,delete'],
            'task_id' => ['required_if:action,abort,delete','string','max:128'],
            'query' => ['required_if:action,start','string','max:512'],
            'links' => ['required_without:query','array','min:1','max:100'],
            'links.*' => ['url'],
            'filters' => ['sometimes','array'],
            'format'  => ['sometimes','in:json,csv,excel'],
        ];
    }
}
