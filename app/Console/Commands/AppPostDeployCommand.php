<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'app:post-deploy', description: 'Run post-deployment tasks, such as adding holidays in attendance')]
final class AppPostDeployCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:post-deploy';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run tasks after deployment to ensure data consistency and updates';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->performPreDeployment();
        $this->callSilent('storage:link');
        $this->callSilent('debugbar:clear');
        $this->callSilent('optimize:clear');
        $this->call('migrate');
        $this->performPostDeployment();
    }

    /**
     * Perform pre-deployment tasks.
     */
    protected function performPreDeployment(): void
    {
        // ...
    }

    /**
     * Perform post-deployment tasks.
     */
    protected function performPostDeployment(): void
    {
        // ...
    }
}
