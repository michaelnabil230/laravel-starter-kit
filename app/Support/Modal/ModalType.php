<?php

declare(strict_types=1);

namespace App\Support\Modal;

enum ModalType: string
{
    case Modal = 'modal';

    case Slideover = 'slideover';
}
