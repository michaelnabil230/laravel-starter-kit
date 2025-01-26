<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\User;
use App\Notifications\Birthday;
use Illuminate\Console\Command;
use Illuminate\Contracts\Console\Isolatable;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'check:birthdays', description: 'Check for birthdays and send notifications')]
final class CheckBirthdaysCommand extends Command implements Isolatable
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:birthdays';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for birthdays and send notifications';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $users = User::query()
            ->whereDay('birth_date', now()->format('d'))
            ->whereMonth('birth_date', now()->format('m'))
            ->get(['id', 'name', 'email', 'fcm_token']);

        foreach ($users as $user) {
            $user->notify(new Birthday);
        }

        $this->components->info('Birthday emails sent successfully.');
    }
}
