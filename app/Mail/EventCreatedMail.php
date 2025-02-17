<?php

namespace App\Mail;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EventCreatedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $event;

    public $user;

    /**
     * Create a new message instance.
     */
    public function __construct($event, $user)
    {
        $this->event = $event;
        $this->user = $user;
    }

    public function build(): EventCreatedMail
    {
        $this->event->formated_time = Carbon::parse($this->event->time)->format('H:i');

        return $this->markdown('emails.event_created')
            ->with([
                'event' => $this->event,
                'user' => $this->user,
            ]);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Event Created Successfully',
        );
    }
}
