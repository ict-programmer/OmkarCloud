<?php

namespace App\Data\Request\Captions;

use App\Traits\PubliishIOTrait;
use Spatie\LaravelData\Attributes\Computed;
use Spatie\LaravelData\Attributes\Hidden;
use Spatie\LaravelData\Data;

class AiTranslateSubmitData extends Data
{
    use PubliishIOTrait;

    #[Computed]
    public string $videoUrl;

    public function __construct(
        #[Hidden]
        public string $videoCid,
        public string $sourceLanguage,
        public string $targetLanguage,
    ) {
        $this->videoUrl = $this->getPublishUrl($this->videoCid);
    }
}
