<?php

namespace App\Http\Resources\Runwayml;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VideoProcessingResource extends JsonResource
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
            'message' => "Video processing task created successfully",
            'data' => $this->data,
        ];
    }
}
