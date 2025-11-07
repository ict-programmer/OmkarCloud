<?php

namespace App\Http\Requests\GoogleSheet;

use Illuminate\Foundation\Http\FormRequest;

class SearchGoogleSheetRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'sheet_id' => 'required|string',
            'sheet_name' => 'required|string',
            'client_id' => 'nullable|string',
            'project_id' => 'nullable|string',
            'auth_uri' => 'nullable|string',
            'token_uri' => 'nullable|string',
            'auth_provider_x509_cert_url' => 'nullable|string',
            'client_secret' => 'nullable|string',
            'redirect_uris' => 'nullable|array',
            'interface' => 'nullable|string',
        ];
    }
}
