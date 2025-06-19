<?php

namespace App\Http\Requests\Shutterstock;

use Illuminate\Foundation\Http\FormRequest;

class AddToVideoCollectionRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'collection_id' => 'required|string|regex:/^[1-9][0-9]*$/',
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|string|regex:/^[1-9][0-9]*$/',
        ];
    }
} 