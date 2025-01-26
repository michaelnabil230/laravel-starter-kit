<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Admin;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

final class ImportHasFailed extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(Admin $admin): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(Admin $admin): MailMessage
    {
        return (new MailMessage)
            ->from('noreply@app.com', 'no-reply')
            ->subject('Your Data is Import Failed')
            ->greeting("Hello {$admin->name},")
            ->line('Your requested import file is failed. Please try again.')
            ->salutation('Thank you for using our application!');
    }
}
