<?php

namespace App\Http\Requests\BillionMail;

use Illuminate\Foundation\Http\FormRequest;

class SendBatchEmailRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'recipients' => ['required', 'array', 'min:1'],
            'recipients.*' => ['email'],
            'addresser' => ['required', 'email'],
            'attribs' => ['required', 'array'],
            'attribs.subject' => ['required', 'string'],
            'attribs.body' => ['required', 'string'],
        ];
    }

}
