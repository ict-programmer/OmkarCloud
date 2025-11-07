<?php

namespace App\Data\Request\Placid;

use Spatie\LaravelData\Data;

class RetrieveVideoData extends Data
{
    public function __construct(
        public int $video_id,
    ) {}
}
