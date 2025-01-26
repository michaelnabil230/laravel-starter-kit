<?php

declare(strict_types=1);

namespace App\Bootstrap;

use Illuminate\Foundation\Configuration\Exceptions;
use Sentry\Laravel\Integration;

final class ExceptionBootstrapper
{
    public function __invoke(Exceptions $exceptions): void
    {
        if (app()->isProduction()) {
            Integration::handles($exceptions);
        }
    }
}
