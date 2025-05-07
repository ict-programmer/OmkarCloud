<?php

namespace App\Http\Resources\ClaudeAPI;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TextTranslateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'status' => $this->status,
            'data' => [
                'translated_text' => $this->message,
                'error' => $this->error ?? null,
            ],
        ];
    }
}
