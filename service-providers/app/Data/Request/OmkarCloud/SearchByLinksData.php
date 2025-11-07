<?php

namespace App\Data\Request\OmkarCloud;

use Spatie\LaravelData\Data;

class SearchByLinksData extends Data
{
    /** @param array<int,string> $urls */
    public function __construct(
        public array $urls,
        public ?string $format = 'json',
    ) {}
}
