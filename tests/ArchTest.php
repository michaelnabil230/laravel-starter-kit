<?php

declare(strict_types=1);

arch()->preset()->php();

arch()->preset()->security()->ignoring('assert');

arch('strict types')
    ->expect('App')
    ->toUseStrictTypes();