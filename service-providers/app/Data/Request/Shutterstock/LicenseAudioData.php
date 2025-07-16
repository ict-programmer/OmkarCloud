<?php

namespace App\Data\Request\Shutterstock;

use Spatie\LaravelData\Data;

class LicenseAudioData extends Data
{
    public function __construct(
        public array $audio_tracks,
        public ?string $search_id = null,
    ) {}
} 