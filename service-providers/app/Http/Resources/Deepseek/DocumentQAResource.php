<?php

namespace App\Http\Resources\Deepseek;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DocumentQAResource extends JsonResource
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
                'reasoning' => $this->message,
            ] : [
                'error' => $this->error ?? 'Unknown error',
                'request_id' => $this->id ?? null
            ],
            'timestamp' => now()->toIso8601String()
        ];
    }
}
