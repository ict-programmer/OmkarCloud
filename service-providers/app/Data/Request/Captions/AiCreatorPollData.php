<?php

namespace App\Data\Request\Captions;

use Spatie\LaravelData\Data;

class AiCreatorPollData extends Data
{
    public function __construct(
        public string $operationId,
    ) {}
}
