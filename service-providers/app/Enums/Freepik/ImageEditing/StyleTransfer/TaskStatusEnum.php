<?php

namespace App\Enums\Freepik\ImageEditing\StyleTransfer;

use App\Traits\BaseEnumTrait;

enum TaskStatusEnum: string
{
    use BaseEnumTrait;

    case IN_PROGRESS = 'IN_PROGRESS';
    case COMPLETED = 'COMPLETED';
    case FAILED = 'FAILED';
}
