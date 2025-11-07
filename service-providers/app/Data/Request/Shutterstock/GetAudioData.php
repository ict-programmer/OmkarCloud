<?php

namespace App\Data\Request\Shutterstock;

use Spatie\LaravelData\Data;

class GetAudioData extends Data
{
    public function __construct(
        public string $audio_id,
    ) {}
} 