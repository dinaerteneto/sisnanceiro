<?php

namespace Sisnanceiro\Services;

use App\Mail\EventGuest as MailEventGuest;
use Illuminate\Support\Facades\Mail;
use Sisnanceiro\Helpers\Validator;
use Sisnanceiro\Models\EventGuest;
use Sisnanceiro\Repositories\EventGuestRepository;

class EventGuestService extends Service
{

    protected $rules = [
        'create' => [
            'event_id'    => 'required|int',
            'email'       => 'required',
            'person_name' => 'required',
            'token_email' => 'required',
            'status'      => 'required',
        ],
        'update' => [
            'event_id'    => 'required|int',
            'email'       => 'required',
            'person_name' => 'required',
            'token_email' => 'required',
            'status'      => 'required',
        ],
    ];

    public function __construct(Validator $validator, EventGuestRepository $repository)
    {
        $this->validator  = $validator;
        $this->repository = $repository;
    }

    public function allMainGuest($eventId)
    {
        return $this->repository->allMainGuest($eventId);
    }

    public function sendInvoiceToMail(EventGuest $eventGuest)
    {
        Mail::to($eventGuest)
            ->send(new MailEventGuest($eventGuest));
    }
}
