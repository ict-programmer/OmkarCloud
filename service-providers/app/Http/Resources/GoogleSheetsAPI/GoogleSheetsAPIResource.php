<?php

namespace App\Http\Resources\GoogleSheetsAPI;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GoogleSheetsAPIResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        if ($this->resource instanceof \Exception) {
            return [
                'status' => 'error',
                'data' => [
                    'message' => $this->resource->getMessage(),
                    'code' => $this->resource->getCode(),
                ],
                'timestamp' => now()->toIso8601String(),
            ];
        }

        return [
            'status' => 'success',
            'data' => $this->resource,
            'timestamp' => now()->toIso8601String(),
        ];
    }
}