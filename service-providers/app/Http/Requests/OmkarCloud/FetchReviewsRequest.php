<?php

namespace App\Http\Requests\OmkarCloud;

use Illuminate\Foundation\Http\FormRequest;

class FetchReviewsRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'identifier' => ['required','string','max:1024'], // place_id or URL
            'limit'      => ['sometimes','integer','min:1','max:1000'],
            'format'     => ['sometimes','in:json,csv,excel'],
        ];
    }
}
