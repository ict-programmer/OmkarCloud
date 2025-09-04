<?php

namespace App\Data\OmkarCloud\Requests;

readonly class SearchLinks implements ArrayableRequest
{
    /** @param string[] $links */
    public function __construct(
        public array $links,
        public ?array $filters = null,
        public ?string $format = 'json'
    ) {}

    public function toArray(): array
    {
        return array_filter([
            'links'   => $this->links,
            'filters' => $this->filters,
            'format'  => $this->format,
        ], fn($v) => $v !== null);
    }
}
