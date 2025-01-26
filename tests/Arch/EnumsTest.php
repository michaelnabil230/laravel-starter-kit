<?php

declare(strict_types=1);

arch('enums')
    ->expect('App\Enums')
    ->toBeEnums()
    ->ignoring('App\Enums\Concerns')
    ->toExtendNothing();
