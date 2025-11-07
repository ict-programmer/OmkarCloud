<?php

namespace App\Data\Request\FFMpeg;

use Spatie\LaravelData\Data;

class ConcatenateData extends Data
{
    public function __construct(
        public array $input_files
    ) {}
}
