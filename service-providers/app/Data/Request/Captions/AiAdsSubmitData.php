<?php

namespace App\Data\Request\Captions;

use App\Traits\PubliishIOTrait;
use Spatie\LaravelData\Attributes\Computed;
use Spatie\LaravelData\Attributes\Hidden;
use Spatie\LaravelData\Data;

class AiAdsSubmitData extends Data
{
    use PubliishIOTrait;

    #[Computed]
    public array $mediaUrls;

    public function __construct(
        public string $script,
        public string $creatorName,
        #[Hidden]
        public array $mediaCids,
        public ?string $resolution,
    ) {
        if (!empty($this->mediaCids)) {
            $this->mediaUrls = array_map([$this, 'getPublishUrl'], $this->mediaCids);
        }
    }
}
