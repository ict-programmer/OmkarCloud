<?php

namespace App\Http\Requests\Pexels;

use Illuminate\Foundation\Http\FormRequest;

class GetPhotoRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'id.required' => __('The photo ID is required.'),
            'id.string' => __('The photo ID must be a string.'),
        ];
    }

    public function validationData(): array
    {
        return array_merge($this->all(), $this->route()?->parameters() ?? []);
    }
}
