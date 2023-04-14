<?php

namespace App\Mail;

use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use Illuminate\Bus\Queueable;
class WelcomeMail extends Mailable
{
    use Queueable, SerializesModels;
     public $user;
    /**
     * Create a new message instance.
     */
    public function __construct(User $user)
    {
    $this->user=$user;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Welcome Mail send ',
        );
    }
    public function build()
    {
        return $this->subject('Welcome to My Application')
                    ->view('emails.welcome')
                    ->with([ 'user' => $this->user,]);                      
                  
                }
    /**
     * Get the message content definition.
     */

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