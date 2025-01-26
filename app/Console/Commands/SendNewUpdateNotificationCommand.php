<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\User;
use App\Notifications\NewUpdate;
use Illuminate\Console\Command;
use Illuminate\Contracts\Console\Isolatable;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'send-new-update-notification', description: 'Send notification for a new update to all users')]
final class SendNewUpdateNotificationCommand extends Command implements Isolatable
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send-new-update-notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send notification for a new update to all users';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        User::query()
            ->whereNotNull('fcm_token')
            ->select(['id', 'fcm_token'])
            ->cursor()
            ->each(fn (User $user) => $user->notify(new NewUpdate));

        $this->components->info('Notifications sent to all users successfully.');

        return self::SUCCESS;
    }
}
