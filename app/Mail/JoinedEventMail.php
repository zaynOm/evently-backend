<?php

namespace App\Mail;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class JoinedEventMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user;

    public $event;

    /**
     * Create a new message instance.
     */
    public function __construct($event, $user)
    {
        $this->event = $event;
        $this->user = $user;
    }

    public function build(): JoinedEventMail
    {
        $this->event->formated_time = Carbon::parse($this->event->time)->format('H:i');

        return $this->markdown('emails.joined_event')
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
            subject: "Joined Event {$this->event->title}",
        );
    }
}
