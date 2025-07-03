<?php

namespace App\Data\Request\Captions;

use App\Traits\PubliishIOTrait;
use Spatie\LaravelData\Attributes\Computed;
use Spatie\LaravelData\Attributes\Hidden;
use Spatie\LaravelData\Data;

class AiTwinCreateData extends Data
{
    use PubliishIOTrait;

    #[Computed]
    public string $videoUrl;

    #[Computed]
    public array $calibrationImageUrls;

    public function __construct(
        public string $name,
        #[Hidden]
        public string $videoCid,
        #[Hidden]
        public array $calibrationImageCids,
        public ?string $language,
    ) {
        $this->videoUrl = $this->getPublishUrl($this->videoCid);
        $this->calibrationImageUrls = array_map([$this, 'getPublishUrl'], $this->calibrationImageCids);
    }
}
