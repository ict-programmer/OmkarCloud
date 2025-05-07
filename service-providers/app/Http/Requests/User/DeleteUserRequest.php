<?php

namespace App\Http\Requests\User;

use App\Http\Requests\BaseFormRequest;

class DeleteUserRequest extends BaseFormRequest
{

    public function rules(): array
    {
        return [
            'id' => 'required|string',
        ];
    }
}
