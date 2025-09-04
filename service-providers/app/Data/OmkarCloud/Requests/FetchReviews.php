<?php

namespace App\Data\OmkarCloud\Requests;

readonly class FetchReviews implements ArrayableRequest
{
    // You can pass a place_id or a Google Maps link.
    public function __construct(
        public string $identifier,
        public ?int $limit = null,
        public ?string $format = 'json'
    ) {}

    public function toArray(): array
    {
        return array_filter([
            // Align with the real API parameter name you use (place_id or link)
            'identifier' => $this->identifier,
            'limit'      => $this->limit,
            'format'     => $this->format,
        ], fn($v) => $v !== null);
    }
}
