<?php

declare(strict_types=1);

namespace App\Bootstrap;

use Illuminate\Console\Scheduling\Schedule;
use Spatie\ScheduleMonitor\Models\MonitoredScheduledTaskLogItem;

final class ScheduleBootstrapper
{
    public function __invoke(Schedule $schedule): void
    {
        $schedule->command('queue:work --stop-when-empty')
            ->everyMinute()
            ->withoutOverlapping()
            ->sendOutputTo(storage_path('/logs/queue-jobs.log'));

        $schedule->command('model:prune')->daily();
        $schedule->command('model:prune', ['--model' => MonitoredScheduledTaskLogItem::class])->daily();
        $schedule->command('auth:clear-resets')->everyFifteenMinutes();

        $schedule->command('horizon')->everyMinute()->withoutOverlapping();
        $schedule->command('horizon:snapshot')->everyFiveMinutes();

        $schedule->command('telescope:prune')->daily();

        $schedule->command('pulse:check')->everyMinute()->withoutOverlapping();
        $schedule->command('pulse:work')->everyFiveMinutes()->withoutOverlapping();
        $schedule->command('pulse:clear')->weekly();

        $schedule->command('backup:clean')->weeklyOn(1, '6:30');
        $schedule->command('backup:run')->weeklyOn(1, '7:00');

        $schedule->command('auth:clear-code-resets --isolated')->monthly();

        $schedule->command('check:birthdays --isolated')->daily();

        $schedule->command('health:check')->everyMinute();
        $schedule->command('health:schedule-check-heartbeat')->everyMinute();
        $schedule->command('health:queue-check-heartbeat')->everyMinute();
    }
}
