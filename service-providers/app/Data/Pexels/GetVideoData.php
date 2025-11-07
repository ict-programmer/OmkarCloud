<?php

namespace App\Data\Pexels;

use Spatie\LaravelData\Data;

class GetVideoData extends Data
{
    public function __construct(
        public string $id,
    ) {}
}
