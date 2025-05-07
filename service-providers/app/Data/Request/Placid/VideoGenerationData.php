<?php

namespace App\Data\Request\Placid;

use Spatie\LaravelData\Data;

class VideoGenerationData extends Data
{
    public function __construct(
        public array $clips,
    ) {}
}
