<?php

namespace App\Http\Requests\OmkarCloud;

use Illuminate\Foundation\Http\FormRequest;

class TaskIdRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'task_id' => ['required','string','max:128'],
            'format'  => ['sometimes','in:json,csv,excel'],
        ];
    }
}
