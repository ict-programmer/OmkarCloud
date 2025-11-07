<?php

namespace App\Http\Resources\ClaudeAPI;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TextSummarizeResource extends JsonResource
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
                'text' => $this->message,
                'summary_length' => $this->summary_length ?? null,
                'error' => $this->error ?? null,
            ],
        ];
    }
}
