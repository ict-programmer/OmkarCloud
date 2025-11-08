<?php

namespace App\Data\Request\FFMpeg;

use Spatie\LaravelData\Data;

class FileInspectionData extends Data
{
    public function __construct(
        public string $input
    ) {}
}
