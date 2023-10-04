<?php

namespace App\Mail;

use App\Models\Campaign;
use DateTime;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Headers;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\App;
use Illuminate\Queue\Middleware\ThrottlesExceptions;

class CampaignEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $campaign;
    public $token;

    /**
     * Create a new message instance.
     */
    public function __construct(Campaign $campaign, $token)
    {
        App::setLocale('ru');

        $this->campaign = $campaign;
        $this->token = $token;
    }

    public function middleware(): array
    {
        return [new ThrottlesExceptions(10, 10)];
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->campaign->subject,
        );
    }

    /**
     * Get the message headers.
     */
    public function headers(): Headers
    {
        return new Headers(
            text: [
                'List-Unsubscribe' => url('/api/v2/unsubscribe/' . $this->token),
            ],
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $unsubscribeUrl = config('app.origin') . '/unsubscribe/' . $this->token;

        return new Content(
            view: 'emails.campaign',
            with: [
                'content' => $this->campaign->body,
                'unsubscribeUrl' => $unsubscribeUrl,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
