<?php

declare(strict_types=1);

namespace App\Support\Modal;

enum ModalPosition: string
{
    case Bottom = 'bottom';

    case Left = 'left';

    case Right = 'right';

    case Top = 'top';
}
