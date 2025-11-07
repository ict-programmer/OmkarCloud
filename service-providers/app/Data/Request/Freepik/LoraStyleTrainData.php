<?php

namespace App\Data\Request\Freepik;

use App\Traits\PubliishIOTrait;
use Spatie\LaravelData\Attributes\Computed;
use Spatie\LaravelData\Attributes\Hidden;
use Spatie\LaravelData\Data;

class LoraStyleTrainData extends Data
{
    use PubliishIOTrait;

    #[Computed]
    public array $images;

    public function __construct(
        public string $name,
        public string $quality,
        #[Hidden]
        public array $image_cids,
        public ?string $description = null,
    ) {
        if (!empty($image_cids)) {
            $this->images = array_map([$this, 'getPublishUrl'], $image_cids);
        }
    }
}
