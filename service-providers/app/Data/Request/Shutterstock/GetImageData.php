<?php

namespace App\Data\Request\Shutterstock;

use Spatie\LaravelData\Data;

class GetImageData extends Data
{
    public function __construct(
        public string $image_id,
    ) {}
} 