<?php

namespace App\Http\Resources\Runwayml;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskManagementResource extends JsonResource
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
