<?php

namespace App\Data\GettyImages;

use Spatie\LaravelData\Data;

class ImageSearchByImageUploadData extends Data
{
    public function __construct(
        public string $file_name,
        public string $file,
    ) {}
}
