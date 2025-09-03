<?php

namespace App\Data\Request\Shotstack;

use Spatie\LaravelData\Data;

class GetVideoMetadataData extends Data
{
    public function __construct(
        public string $id
    ) {}
}
