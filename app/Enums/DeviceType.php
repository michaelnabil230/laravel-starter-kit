<?php

declare(strict_types=1);

namespace App\Enums;

use App\Enums\Concerns\MagicalEnum;

enum DeviceType: string
{
    use MagicalEnum;

    case IOS = 'ios';

    case ANDROID = 'android';
}
