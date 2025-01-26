<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

final class Welcome extends Notification implements ShouldHandleEventsAfterCommit, ShouldQueue
{
    use Queueable;

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(User $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(User $notifiable): MailMessage
    {
        return (new MailMessage)
            ->from('noreply@app.com', 'no-reply')
            ->subject('Welcome Email')
            ->greeting("Hello, {$notifiable->name}")
            ->line('Welcome to App. Please note that an account has been registered with this email and you can now use the application.')
            ->salutation('Thank you for using our application!');
    }
}
