<?php

namespace App\Data\Request\OmkarCloud;

use Spatie\LaravelData\Data;

class SearchLinksData extends Data
{
    /** @param string[] $links */
    public function __construct(
        public array $links,
        public ?array $filters = null,
        public ?string $format = 'json',
    ) {}
}
