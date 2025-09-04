<?php

namespace App\Http\Requests\OmkarCloud;

use Illuminate\Foundation\Http\FormRequest;

class SortLogicRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'task_id' => ['sometimes','string','max:128'],
            'mode'    => ['sometimes','in:best_customer'],
            'format'  => ['sometimes','in:json,csv,excel'],
        ];
    }
}
