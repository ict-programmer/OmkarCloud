<?php

namespace App\Http\Requests\Shutterstock;

use Illuminate\Foundation\Http\FormRequest;

class SearchAudioRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'query' => 'required|string|max:255',
            'sort' => 'nullable|string|in:score,ranking_all,artist,title,bpm,freshness,duration',
        ];
    }
} 