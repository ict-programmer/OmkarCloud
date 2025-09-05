<?php

namespace App\Http\Requests\BillionMail;

use Illuminate\Foundation\Http\FormRequest;

class SendEmailRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'recipient' => ['required', 'email'],
            'addresser' => ['required', 'email'],
            'attribs'   => ['nullable', 'array'],
        ];
    }
}
