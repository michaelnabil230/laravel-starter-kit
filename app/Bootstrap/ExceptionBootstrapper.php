<?php

declare(strict_types=1);

namespace App\Bootstrap;

use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Http\Request;
use Sentry\Laravel\Integration;

final class ExceptionBootstrapper
{
    public function __invoke(Exceptions $exceptions): void
    {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );

        if (app()->isProduction()) {
            Integration::handles($exceptions);
        }
    }
}
