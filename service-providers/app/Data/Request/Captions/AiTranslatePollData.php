<?php

namespace App\Data\Request\Captions;

use Spatie\LaravelData\Data;

class AiTranslatePollData extends Data
{
    public function __construct(
        public string $operationId,
    ) {}
}
