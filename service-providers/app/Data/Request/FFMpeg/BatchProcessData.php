<?php

namespace App\Data\Request\FFMpeg;

use App\Models\ServiceProvider;
use Spatie\LaravelData\Data;

class BatchProcessData extends Data
{
    public function __construct(
        public array $services,
        public ServiceProvider $ffmpegProvider,
    ) {}
}
