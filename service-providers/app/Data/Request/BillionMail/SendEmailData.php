<?php

namespace App\Data\Request\BillionMail;

use Spatie\LaravelData\Data;

class SendEmailData extends Data
{
    public function __construct(
        public string $recipient,
        public string $addresser,
        public array $attribs
    ) {}
}
