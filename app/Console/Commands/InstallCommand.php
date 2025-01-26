<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use Illuminate\Contracts\Console\Isolatable;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Process;
use Symfony\Component\Console\Attribute\AsCommand;

use function Laravel\Prompts\info;
use function Laravel\Prompts\warning;

#[AsCommand(name: 'project:install', description: 'Install the project.')]
final class InstallCommand extends Command implements Isolatable
{
    use ConfirmableTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'project:install {--force : Force the operation to run when in production}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install the project.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        if (! $force = $this->confirmToProceed()) {
            return self::FAILURE;
        }

        info('Starting App installation...');

        $this->callSilent('migrate:refresh', [
            '--force' => $force,
        ]);
        $this->call('db:seed', [
            '--force' => $force,
        ]);
        $this->callSilent('storage:link');
        $this->callSilent('debugbar:clear');
        $this->callSilent('optimize:clear');
        $this->callSilent('schedule-monitor:sync');

        // Run npm install
        if (! File::exists('node_modules')) {
            info('Running npm install...');
            Process::run('npm install');
        } else {
            warning('Node modules already exist. Skipping npm install.');
        }

        info('App installation completed successfully!');
        info('👉 Run `php artisan solo` or `composer run dev` to start the local server.');
        info('Keep creating. 🫡');

        return self::SUCCESS;
    }
}
