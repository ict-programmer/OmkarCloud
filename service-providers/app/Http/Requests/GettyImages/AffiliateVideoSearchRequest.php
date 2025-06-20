<?php

namespace App\Http\Requests\GettyImages;

use Illuminate\Foundation\Http\FormRequest;

class AffiliateVideoSearchRequest extends FormRequest
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
            'phrase' => 'required|string|max:255',
            'fields' => 'nullable|array',
            'fields.language' => 'nullable|string|in:cs,de,en-GB,en-US,es,fi,fr,hu,id,it,ja,ko,nl,pl,pt-BR,pt-PT,ro,ru,sv,th,tr,uk,vi,zh-HK',
            'fields.countryCode' => 'nullable|string|size:3|alpha',
        ];
    }
}
