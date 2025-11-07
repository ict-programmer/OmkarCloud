<?php

namespace App\Http\Resources\Qwen;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QwenTextSummarizationResource extends JsonResource
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
            'data' => $this->status ? [
                'summary' => $this->message,
            ] : [
                'error' => $this->error ?? 'Unknown error',
                'request_id' => $this->id ?? null
            ],
            'timestamp' => now()->toIso8601String()
        ];
    }
}
