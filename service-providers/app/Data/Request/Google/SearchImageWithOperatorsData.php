<?php

namespace App\Data\Request\Google;

use Spatie\LaravelData\Data;

class SearchImageWithOperatorsData extends Data
{
    public function __construct(
        public string $q,

        public ?string $c2coff,
        public ?string $cr,
        public ?string $dateRestrict,
        public ?string $exactTerms,
        public ?string $excludeTerms,
        public ?string $fileType,
        public ?string $filter,
        public ?string $gl,
        public ?string $googlehost,
        public ?string $highRange,
        public ?string $hl,
        public ?string $hq,

        public ?string $imgColorType,
        public ?string $imgDominantColor,
        public ?string $imgSize,
        public ?string $imgType,

        public ?string $linkSite,
        public ?string $lowRange,
        public ?string $lr,
        public ?int    $num,
        public ?string $orTerms,

        public ?string $rights,
        public ?string $safe,
        public ?string $siteSearch,
        public ?string $siteSearchFilter,

        public ?string $sort,
        public ?int    $start,
    ) {}
}
