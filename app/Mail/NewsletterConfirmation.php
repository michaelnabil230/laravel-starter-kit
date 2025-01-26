<?php

declare(strict_types=1);

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

final class NewsletterConfirmation extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(
                name: 'App',
                address: 'noreply@app.com',
            ),
            subject: 'Newsletter Confirmation',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'mail.newsletter-confirmation',
        );
    }
}
