<?php

namespace App\Data\Request\GoogleSheet;

use Spatie\LaravelData\Data;

class SearchGoogleSheetData extends Data
{
    public function __construct(
        public string $sheet_id,
        public string $sheet_name,
        public ?string $client_id,
        public ?string $project_id,
        public ?string $auth_uri,
        public ?string $token_uri,
        public ?string $auth_provider_x509_cert_url,
        public ?string $client_secret,
        public ?array $redirect_uris,
        public ?string $interface,
    ) {}
}
