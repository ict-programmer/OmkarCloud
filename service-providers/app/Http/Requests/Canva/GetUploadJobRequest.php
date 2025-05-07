<?php

namespace App\Http\Requests\Canva;

use Illuminate\Foundation\Http\FormRequest;

class GetUploadJobRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'job_id' => 'required|string',
        ];
    }

    /**
     * Get error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'job_id.required' => 'The job_id field is required.',
        ];
    }
}
