<?php

declare(strict_types=1);

namespace App\Providers;

use AaronFrancis\Solo\Facades\Solo;
use Illuminate\Support\ServiceProvider;

final class SoloServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        Solo::useTheme('dark')
            ->addCommands([
                'Logs' => 'php artisan pail',
                'Vite' => 'npm run dev',
            ])
            ->addLazyCommands([
                'Queue' => 'php artisan queue:listen --tries=1',
                // 'Reverb' => 'php artisan reverb:start',
                'Pint' => './vendor/bin/pint --ansi',
            ])
            ->allowCommandsAddedFrom([
                //
            ]);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
