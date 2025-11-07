<?php

namespace App\Data\Request\FFMpeg;

use Spatie\LaravelData\Data;

class StreamCopyData extends Data
{
    public function __construct(
        public string $input,
        public array $streams
    ) {}
}
