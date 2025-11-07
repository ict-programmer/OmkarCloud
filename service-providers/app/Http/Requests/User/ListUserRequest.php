<?php

namespace App\Http\Requests\User;

use App\Http\Requests\BaseFormRequest;

class ListUserRequest extends BaseFormRequest
{
    protected bool $havePagination = true;

    public function rules(): array
    {
        return [
            'search' => 'nullable|string',
        ];
    }
}
