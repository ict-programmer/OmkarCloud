<?php

namespace App\Http\Requests\Midjourney;

use App\Http\Requests\BaseFormRequest;

class GetTaskRequest extends BaseFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'task_id' => 'required|string|uuid',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'task_id.required' => 'Task ID is required to check the status.',
            'task_id.string' => 'Task ID must be a valid string.',
            'task_id.uuid' => 'Task ID must be a valid UUID format.',
        ];
    }
} 