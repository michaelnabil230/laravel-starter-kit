<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\PasswordResetCode;
use Illuminate\Console\Command;
use Illuminate\Contracts\Console\Isolatable;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'auth:clear-code-resets', description: 'Flush expired password reset codes')]
final class ClearCodeResetsCommand extends Command implements Isolatable
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auth:clear-code-resets';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Flush expired password reset codes';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        PasswordResetCode::deleteExpired();

        $this->components->info('Expired reset code cleared successfully.');
    }
}
