<?php

namespace App\Data\Request\Freepik;

use App\Traits\PubliishIOTrait;
use Spatie\LaravelData\Attributes\Computed;
use Spatie\LaravelData\Attributes\Hidden;
use Spatie\LaravelData\Data;

class LoraCharacterTrainData extends Data
{
    use PubliishIOTrait;

    #[Computed]
    public array $images;

    public function __construct(
        public string $name,
        public string $quality,
        public string $gender,
        #[Hidden]
        public array $image_cids,
        public ?string $description,
    ) {

        if (!empty($this->image_cids)) {
            $this->images = array_map([$this, 'getPublishUrl'], $this->image_cids);
        }
    }
}
