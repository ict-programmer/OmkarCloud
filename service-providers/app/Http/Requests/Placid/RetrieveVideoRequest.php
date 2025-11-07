<?php

namespace App\Http\Requests\Placid;

use Illuminate\Foundation\Http\FormRequest;

class RetrieveVideoRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'video_id' => 'required|integer',
        ];
    }
}
