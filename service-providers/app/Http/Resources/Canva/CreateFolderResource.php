<?php

namespace App\Http\Resources\Canva;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CreateFolderResource extends JsonResource
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
