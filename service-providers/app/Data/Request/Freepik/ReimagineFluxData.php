<?php

namespace App\Data\Request\Freepik;

use App\Traits\PubliishIOTrait;
use Spatie\LaravelData\Attributes\Computed;
use Spatie\LaravelData\Attributes\Hidden;
use Spatie\LaravelData\Data;

class ReimagineFluxData extends Data
{
    use PubliishIOTrait;

    #[Computed]
    public string $image;

    public function __construct(
        #[Hidden]
        public string $image_cid,
        public ?string $prompt = null,
        public ?string $imagination = null,
        public ?string $aspect_ratio = null,
    ) {
        $this->image = $this->getPublishUrl($this->image_cid);
    }
}
