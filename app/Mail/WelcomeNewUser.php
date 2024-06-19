<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class WelcomeNewUser extends Mailable
{
    use Queueable, SerializesModels;
    public $user;
    public $password, $logoUrl, $loginUrl;
    /**
     * Create a new message instance.
     */
    public function __construct(User $user, $password, $logoUrl,$loginUrl)
    {
        $this->user = $user;
        $this->password = $password;
        $this->logoUrl = $logoUrl;
        $this->loginUrl = $loginUrl;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Welcome New User',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.welcome_new_user',
            with: ['user' => $this->user,
            'password' => $this->password,],
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
