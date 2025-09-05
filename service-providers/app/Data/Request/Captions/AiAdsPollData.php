<?php

namespace App\Data\Request\Captions;

use Spatie\LaravelData\Data;

class AiAdsPollData extends Data
{
    public function __construct(
        public string $operationId,
    ) {}
}
