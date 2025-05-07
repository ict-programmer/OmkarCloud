<?php

namespace App\Http\Requests\Assets;

use App\Http\Requests\BaseFormRequest;

class ListAssetsRequest extends BaseFormRequest
{
    protected bool $havePagination = true;

    public function rules(): array
    {
        return [
            'search' => 'nullable|string',
        ];
    }
}
