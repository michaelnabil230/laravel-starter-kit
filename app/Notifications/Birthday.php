<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Fcm\FcmChannel;
use NotificationChannels\Fcm\FcmMessage;
use NotificationChannels\Fcm\Resources\Notification as FcmNotification;

final class Birthday extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(User $notifiable): array
    {
        return ['mail', FcmChannel::class];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(User $notifiable): MailMessage
    {
        return (new MailMessage)
            ->from('noreply@app.com', 'no-reply')
            ->subject('Happy Birthday')
            ->line("Happy Birthday, $notifiable->name!")
            ->line('Wishing you a fantastic day.')
            ->salutation('Thank you for using our application!');
    }

    /**
     * Get the fcm representation of the notification.
     */
    public function toFcm(User $notifiable): FcmMessage
    {
        return new FcmMessage(notification: new FcmNotification(
            title: "Happy Birthday, $notifiable->name!",
            body: 'Wishing you a fantastic day.',
        ));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(User $notifiable): array
    {
        return [
            //
        ];
    }
}
