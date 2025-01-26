<?php

declare(strict_types=1);

use App\Bootstrap\ExceptionBootstrapper;
use App\Bootstrap\MiddlewareBootstrapper;
use App\Bootstrap\ScheduleBootstrapper;
use Illuminate\Foundation\Application;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
    )
    ->withMiddleware(new MiddlewareBootstrapper)
    ->withExceptions(new ExceptionBootstrapper)
    ->withSchedule(new ScheduleBootstrapper)
    ->create();
