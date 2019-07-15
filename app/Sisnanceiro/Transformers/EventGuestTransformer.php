<?php

namespace Sisnanceiro\Transformers;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use League\Fractal\TransformerAbstract;
use Sisnanceiro\Helpers\Mask;
use Sisnanceiro\Models\EventGuest;

class EventGuestTransformer extends TransformerAbstract
{

    public function transform(EventGuest $guest)
    {
        $carbonCreatedAt = Carbon::createFromFormat('Y-m-d H:i:s', $guest->created_at);
        $carbonUpdatedAt = Carbon::createFromFormat('Y-m-d H:i:s', $guest->updated_at);
        $invitedBy       = $guest->invitedBy()->get()->first();

        return [
            'id'                => $guest->id,
            'payment_method_id' => $guest->payment_method_id,
            'person_name'       => $guest->person_name,
            'email'             => $guest->email,
            'token_email'       => $guest->token_email,
            'value'             => $guest->value,
            'status'            => strtoupper($guest->getStatus()),
            'status_int'        => (int) $guest->status,
            'invitedBy'         => !empty($invitedBy) ? $this->transformInvitedBy($guest->invitedBy()->get()->first()) : null,
            'invitedByMe'       => $this->transformInvitedByMe($guest->invitedByMe()->get()),
            'canCancel'         => $guest->canCancel(),
            'event'             => $this->transformEvent($guest->event()->get()->first()),
            'created_at'        => $carbonCreatedAt->format('d/m/Y'),
            'updated_at'        => $carbonUpdatedAt->format('d/m/Y'),
        ];
    }

    private function transformInvitedBy(EventGuest $guest)
    {
        $carbonCreatedAt = Carbon::createFromFormat('Y-m-d H:i:s', $guest->created_at);
        return [
            'id'                     => $guest->id,
            'payment_method_id'      => $guest->payment_method_id,
            'person_name'            => $guest->person_name,
            'email'                  => $guest->email,
            'status'                 => strtoupper($guest->getStatus()),
            'status_int'             => (int) $guest->status,
            'responsable_of_payment' => !empty($guest->responsable_of_payment) ? 'Eu' : 'Convidado',
            'created_at'             => $carbonCreatedAt->format('d/m/Y'),
        ];
    }

    private function transformInvitedByMe(Collection $guests)
    {
        foreach ($guests as $guest) {
            $data[] = $this->transformInvitedBy($guest);
        }
        $data['total_invited'] = count($guests);
        return $data;
    }

    private function transformEvent($event)
    {
        $carbonStartDate = Carbon::createFromFormat('Y-m-d H:i:s', $event->start_date);
        $carbonEndDate   = Carbon::createFromFormat('Y-m-d H:i:s', $event->end_date);

        return [
            'id'                     => $event->id,
            'name'                   => $event->name,
            'company_url'            => $event->company()->get()->first()->url,
            'start_date'             => $carbonStartDate->format('d/m/Y'),
            'end_date'               => $carbonEndDate->format('d/m/Y'),
            'start_date_BR'          => $carbonStartDate->format('d/m/Y'),
            'end_date_BR'            => $carbonEndDate->format('d/m/Y'),
            'start_time'             => $carbonStartDate->format('H:i'),
            'end_time'               => $carbonEndDate->format('H:i'),
            'people_limit'           => $event->people_limit,
            'guest_limit_per_person' => $event->guest_limit_per_person,
            'value_per_person'       => Mask::currency($event->value_per_person),
            'description'            => $event->description,
            'zipcode'                => $event->zipcode,
            'address'                => $event->address,
            'address_number'         => $event->address_number,
            'city'                   => $event->city,
            'district'               => $event->district,
            'uf'                     => $event->uf,
            'complement'             => $event->complement,
            'reference'              => $event->reference,
            'latitude'               => $event->latitude,
            'longitude'              => $event->longitude,
        ];

    }

}
