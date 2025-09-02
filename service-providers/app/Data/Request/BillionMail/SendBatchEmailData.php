<?php

namespace App\Data\Request\BillionMail;

use Spatie\LaravelData\Data;

class SendBatchEmailData extends Data
{
    /** 
     * @param array<int, string> $recipients
     * @param array<string, mixed> $attribs
     */
    public function __construct(
        public array $recipients,
        public string $addresser,
        public array $attribs
    ) {}
}
