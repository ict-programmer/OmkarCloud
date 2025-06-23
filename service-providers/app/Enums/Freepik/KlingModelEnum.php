<?php

namespace App\Enums\Freepik;

use App\Traits\BaseEnumTrait;

enum KlingModelEnum: string
{
    use BaseEnumTrait;

    case V2_1_MASTER = 'kling-v2-1-master';
    case V2_1_PRO = 'kling-v2-1-pro';
    case V2_1_STD = 'kling-v2-1-std';
    case V2 = 'kling-v2';
    case PRO = 'kling-pro';
    case STD = 'kling-std';

    public function supportsMasking(): bool
    {
        return match ($this) {
            self::V2_1_MASTER, self::V2_1_PRO, self::V2_1_STD, self::PRO, self::STD => true,
            default => false,
        };
    }

    public function supportsImageTail(): bool
    {
        return match ($this) {
            self::PRO, self::STD => true,
            default => false,
        };
    }
}
