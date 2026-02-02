<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UpgradeNoticeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $subject;

    public $user;
    public $domain;
    public $optimalPlan;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($subject, $user, $domain, $optimalPlan)
    {
        $this->subject = $subject;
        $this->user = $user;
        $this->domain = $domain;
        $this->optimalPlan = $optimalPlan;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: $this->subject,
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            view: 'emails.upgrade-notice',
            with: [
                'user' => $this->user,
                'domain' => $this->domain,
                'optimalPlan' => $this->optimalPlan,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}
