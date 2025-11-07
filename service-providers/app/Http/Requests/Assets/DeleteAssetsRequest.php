<?php

namespace App\Http\Requests\Assets;

use App\Http\Requests\BaseFormRequest;

class DeleteAssetsRequest extends BaseFormRequest
{

    public function rules(): array
    {
        return [
            'id' => 'required|string',
        ];
    }
}
