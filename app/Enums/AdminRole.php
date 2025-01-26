<?php

declare(strict_types=1);

namespace App\Enums;

use App\Enums\Concerns\MagicalEnum;

enum AdminRole: string
{
    use MagicalEnum;

    case SUPER_ADMIN = 'super_admin';

    case ADMIN = 'admin';
}
