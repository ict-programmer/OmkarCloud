<?php

namespace App\Data\Request\Shotstack;

use Spatie\LaravelData\Data;

class AssetData extends Data
{
    public function __construct(
        public string $type,
        public ?string $text,
        public ?string $src,
        public ?array $font,
        public ?array $alignment,
        public ?string $width,
        public ?string $height
    ) {}
}

class ClipsData extends Data
{
    public function __construct(
        /** @var AssetData */
        public array $asset,
        public int $start,
        public ?string $length,
        public ?array $transition,
        public ?array $offset,
        public ?string $effect
    ) {}
}

class OutputData extends Data
{
    public function __construct(
        public string $type,
        public int $width,
        public int $height
    ) {}
}

class CreateAssetData extends Data
{
    public function __construct(
        /** @var ClipsData */
        public array $clips,
        /** @var OutputData */
        public array $output
    ) {}
}
