<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Spatie\CpuLoadHealthCheck\CpuLoadCheck;
use Spatie\Health\Checks\Checks\CacheCheck;
use Spatie\Health\Checks\Checks\DatabaseCheck;
use Spatie\Health\Checks\Checks\DatabaseConnectionCountCheck;
use Spatie\Health\Checks\Checks\DebugModeCheck;
use Spatie\Health\Checks\Checks\EnvironmentCheck;
use Spatie\Health\Checks\Checks\HorizonCheck;
use Spatie\Health\Checks\Checks\OptimizedAppCheck;
use Spatie\Health\Checks\Checks\PingCheck;
use Spatie\Health\Checks\Checks\QueueCheck;
use Spatie\Health\Checks\Checks\RedisCheck;
use Spatie\Health\Checks\Checks\ScheduleCheck;
use Spatie\Health\Checks\Checks\UsedDiskSpaceCheck;
use Spatie\Health\Facades\Health;
use Spatie\SecurityAdvisoriesHealthCheck\SecurityAdvisoriesCheck;

final class HealthServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Health::checks([
            EnvironmentCheck::new(),
            CacheCheck::new(),
            PingCheck::new()
                ->url(config('app.url').':6001')
                ->name('Socket'),
            HorizonCheck::new(),
            SecurityAdvisoriesCheck::new(),
            ScheduleCheck::new(),
            RedisCheck::new(),
            QueueCheck::new(),
            DebugModeCheck::new(),
            DatabaseCheck::new(),
            DatabaseConnectionCountCheck::new(),
            UsedDiskSpaceCheck::new(),
            CpuLoadCheck::new()
                ->failWhenLoadIsHigherInTheLast5Minutes(2.0)
                ->failWhenLoadIsHigherInTheLast15Minutes(1.5),
            OptimizedAppCheck::new()
                ->checkEvents()
                ->checkConfig(),
        ]);
    }
}
