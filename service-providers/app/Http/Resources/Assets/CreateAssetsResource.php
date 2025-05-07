<?php

namespace App\Http\Resources\Assets;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CreateAssetsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'asset_id' => $this->id,
        ];
    }
}
