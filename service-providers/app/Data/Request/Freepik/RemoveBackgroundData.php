<?php

namespace App\Data\Request\Freepik;

use App\Traits\PubliishIOTrait;
use Spatie\LaravelData\Attributes\Computed;
use Spatie\LaravelData\Attributes\Hidden;
use Spatie\LaravelData\Data;

class RemoveBackgroundData extends Data
{
    use PubliishIOTrait;

    #[Computed]
    public string $image_url;

    public function __construct(
        #[Hidden]
        public string $image_cid
    ) {
        $this->image_url = $this->getPublishUrl($this->image_cid);
    }
}
