<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Admin;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Attachment;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

final class ExportExcel extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(protected string $filePath) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(Admin $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(Admin $notifiable): MailMessage
    {
        return (new MailMessage)
            ->from('noreply@app.com', 'no-reply')
            ->subject('Export data as Excel')
            ->line('Please find the attached export file.')
            ->attach(Attachment::fromStorage($this->filePath))
            ->salutation('Thank you for using our application!');
    }
}
