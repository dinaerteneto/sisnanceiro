<?php

namespace Sisnanceiro\Services;

use App\Mail\EventGuest as MailEventGuest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Sisnanceiro\Helpers\Validator;
use Sisnanceiro\Models\Event;
use Sisnanceiro\Models\EventGuest;
use Sisnanceiro\Repositories\EventGuestRepository;
use Sisnanceiro\Services\PersonService;
use Sisnanceiro\Transformers\EventGuestTransform;

class EventGuestService extends Service
{

    protected $rules = [
        'create' => [
            'event_id'    => 'required|int',
            'email'       => 'required|email',
            'person_name' => 'required',
            'token_email' => 'required',
            'status'      => 'required',
        ],
        'update' => [
            'event_id'    => 'required|int',
            'email'       => 'required|email',
            'person_name' => 'required',
            'token_email' => 'required',
            'status'      => 'required',
        ],
    ];

    public function __construct(Validator $validator, EventGuestRepository $repository, PersonService $personService)
    {
        $this->validator     = $validator;
        $this->repository    = $repository;
        $this->personService = $personService;
    }

    /**
     * prepare data to save in model
     * @param array $data data to map
     * @return array
     */
    public function mapData(array $data)
    {
        $person   = $this->personService->findBy('email', $data['email']);
        $personId = $person ? $person->id : null;
        $event    = $this->repository->find($data['event_id']);

        return [
            'company_id'             => $data['company_id'],
            'event_id'               => $data['event_id'],
            'person_id'              => $personId,
            'person_name'            => $data['name'],
            'email'                  => $data['email'],
            'token_email'            => Hash::make(Str::random()),
            'status'                 => EventGuest::STATUS_WAITING,
            'invited_by_id'          => isset($data['invited_by_id']) && $data['invited_by_id'] !== null ? $data['invited_by_id'] : null,
            'responsable_of_payment' => isset($data['responsable_of_payment']) && $data['responsable_of_payment'] === 'me' ? $data['invited_by_id'] : null,
            'value'                  => !empty($event->value_per_person) && $event->value_per_person > 0 ? $event->value_per_person : 0,
            'whatsapp'               => $data['whatsapp'],
            'student_name'           => $data['student_name'],
        ];
    }

    /**
     * Add Guests on event
     * @param int $eventId id of the event
     * @param array $data
     * @return array
     */
    public function addGuest($eventId, array $data)
    {
        $return           = [];
        $data['event_id'] = $eventId;
        $guest            = false;
        $guest            = $this->findByEmail($eventId, $data['email']);

        if (!$guest) {
            $data = $this->mapData($data);
            if ($repository = $this->store($data, 'create')) {
                $this->sendInvoiceToMail($repository);
                $transform = fractal($repository, new EventGuestTransform());
                $return    = [
                    'success' => true,
                    'data'    => $transform->toArray()['data'],
                ];
            } else {
                $return = [
                    'success' => false,
                    'data'    => $repository,
                ];
            }
        } else {
            $return = [
                'success' => false,
                'data'    => $guest->toArray(),
                'message' => "$guest->person_name ($guest->email),  já foi convidado para este evento.",
            ];
        }
        return $return;

    }

    public function allMainGuest($eventId)
    {
        return $this->repository->allMainGuest($eventId);
    }

    public function confirmed()
    {
        return $this->repository->where(['status' => EventGuest::STATUS_CONFIRMED])->get();
    }

    /**
     * Send Mail to guest
     * @param EventGuest $eventGuest
     * @return bool
     */
    public function sendInvoiceToMail(EventGuest $eventGuest)
    {
        Mail::to($eventGuest)
            ->send(new MailEventGuest($eventGuest));
    }

    /**
     * mass update status guest status
     * @param array $ids id for update
     * @param int $status status for update
     * @return boolean
     */
    public function updateStatus(array $ids, $status)
    {
        return $this->repository
        // ->where(function ($q) {
        //     $q->where('payment_method_id', '<>', PaymentMethod::MONEY)
        //         ->orWhereNull('payment_method_id', 'is', null);
        // })
            ->whereIn('id', $ids)
            ->update(['status' => $status]);
    }

    /**
     * return all categories based on $mainParentCategoryId
     * @param int $mainParentCategoryId
     * @return array
     */
    public function getAll($eventId)
    {
        return $this->repository->allGuests($eventId);
    }

    /**
     * return guest by eventId and email of the guest
     * @param int $eventId
     * @param string $email
     * @return App\Model\EventGuest
     */
    public function findByEmail($eventId, $email)
    {
        return $this->repository
            ->where('event_id', '=', $eventId)
            ->where('email', '=', $email)
            ->get()
            ->first();
    }

    /**
     * Add or Update Guest
     * @param int $eventId
     * @param array $postData
     * @return boolean
     */
    public function addOrUpdate(Event $event, array $postData)
    {
        $bool  = false;
        $guest = $this->findByEmail($event->id, $postData['email']);

        $postData['event_id'] = $event->id;
        if ($guest) {
            if ($this->updateStatus([$guest->id], $postData['status'])) {
                $bool = true;
            }
        } else {
            $postData['company_id'] = $event->company_id;
            if ($this->addGuest($event->id, $postData)) {
                $bool = true;
            }
        }
        return $bool;
    }
}
