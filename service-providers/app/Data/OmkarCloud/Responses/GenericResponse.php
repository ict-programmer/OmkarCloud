<?php

namespace App\Data\OmkarCloud\Responses;

use JsonSerializable;

readonly class GenericResponse implements JsonSerializable
{
    public function __construct(
        public int $status,
        public mixed $data,      // array|scalar|null
        public ?string $error = null
    ) {}

    public static function fromHttp(int $status, mixed $payload): self
    {
        $isOk = $status >= 200 && $status < 300;
        return new self(
            status: $status,
            data:   $isOk ? $payload : null,
            error:  $isOk ? null : (is_array($payload) ? ($payload['message'] ?? json_encode($payload)) : (string)$payload)
        );
    }

    public function jsonSerialize(): mixed
    {
        return [
            'status' => $this->status,
            'data'   => $this->data,
            'error'  => $this->error,
        ];
    }
}
