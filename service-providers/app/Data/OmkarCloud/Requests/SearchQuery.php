<?php

namespace App\Data\OmkarCloud\Requests;

readonly class SearchQuery implements ArrayableRequest
{
    public function __construct(
        public string $query,
        public ?array $filters = null,   
        public ?string $format = 'json'  
    ) {}

    public function toArray(): array
    {
        return array_filter([
            'query'   => $this->query,
            'filters' => $this->filters,
            'format'  => $this->format,
        ], fn($v) => $v !== null);
    }
}
