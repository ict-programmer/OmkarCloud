<?php

namespace App\Data\Request\Claude;

use Spatie\LaravelData\Data;

class QuestionAnswerData extends Data
{
    public function __construct(
        public string $question,
        public string $context,
        public int $max_tokens,
        public ?string $model = null
    ) {}
}
