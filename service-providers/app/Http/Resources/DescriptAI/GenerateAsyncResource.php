<?php

namespace App\Http\Resources\DescriptAI;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GenerateAsyncResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'status' => true,
            'message' => "Overdub generated async task successfully",
            'data' => $this->data,
        ];
    }
}
