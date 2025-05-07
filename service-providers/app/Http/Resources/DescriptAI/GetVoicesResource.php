<?php

namespace App\Http\Resources\DescriptAI;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GetVoicesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return $this->data;
    }
}
