<?php

namespace App\Data\Request\Shutterstock;

use Spatie\LaravelData\Data;

class SearchAudioData extends Data
{
    public function __construct(
        public string $query,
        public ?string $sort = 'score',
    ) {}
} 