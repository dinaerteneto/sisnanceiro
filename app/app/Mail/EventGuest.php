<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Sisnanceiro\Models\EventGuest as EventGuestModel;

class EventGuest extends Mailable
{
    use Queueable, SerializesModels;

    public $eventGuest;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(EventGuestModel $eventGuest)
    {
        $this->eventGuest = $eventGuest;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $eventGuest = $this->eventGuest;
        $event      = $eventGuest->event;
        return $this->subject('Convite para o evento: ' . $event->name)
            ->from(ENV('MAIL_FROM_EMAIL'), ENV('MAIL_FROM_NAME'))
            ->view('event-guest._mail-guest', compact('event', 'eventGuest'));
    }
}
