<?php

namespace App\Data\Request\Captions;

use Spatie\LaravelData\Data;

class AiTranslateSubmitData extends Data
{
    public function __construct(
        public string $videoUrl,
        public string $sourceLanguage,
        public string $targetLanguage,
    ) {}
}
