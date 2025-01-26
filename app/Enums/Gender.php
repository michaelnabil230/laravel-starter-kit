<?php

declare(strict_types=1);

namespace App\Enums;

use App\Enums\Concerns\MagicalEnum;

enum Gender: string
{
    use MagicalEnum;

    case MALE = 'male';

    case FEMALE = 'female';
}
