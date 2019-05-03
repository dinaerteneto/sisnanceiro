<?php

namespace Sisnanceiro\Services;

use Sisnanceiro\Helpers\Validator;
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


}
