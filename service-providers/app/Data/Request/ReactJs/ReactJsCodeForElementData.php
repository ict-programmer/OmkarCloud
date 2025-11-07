<?php

namespace App\Data\Request\ReactJs;

use Spatie\LaravelData\Data;

class ReactJsCodeForElementData extends Data
{
    public function __construct(
        public mixed $element,
    ) {}
}
