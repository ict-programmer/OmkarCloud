<?php

namespace App\Http\Requests\Assets;

use App\Http\Requests\BaseFormRequest;

class CreateAssetsRequest extends BaseFormRequest
{
    protected bool $havePagination = true;

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'status' => 'required|integer|in:0,1',
        ];
    }
}
