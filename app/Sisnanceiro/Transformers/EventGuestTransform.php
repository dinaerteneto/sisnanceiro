<?php

namespace Sisnanceiro\Transformers;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use League\Fractal\TransformerAbstract;
use Sisnanceiro\Models\EventGuest;

class EventGuestTransform extends TransformerAbstract
{

    public function transform(EventGuest $guest)
    {
        $carbonCreatedAt = Carbon::createFromFormat('Y-m-d H:i:s', $guest->created_at);
        return [
            'id'          => $guest->id,
            'person_name' => $guest->person_name,
            'email'       => $guest->email,
            'status'      => strtoupper($guest->getStatus()),
            'created_at'  => $carbonCreatedAt->format('d/m/Y'),
            'invitedByMe' => $this->transformInvitedByMe($guest->invitedByMe()->get()),
            'event'       => $this->transformEvent($guest->event()->get()->first()),
        ];
    }

    private function transformInvitedByMe(Collection $guests)
    {
        foreach ($guests as $guest) {
            $data[] = [
                'id'          => $guest->id,
                'person_name' => $guest->person_name,
                'email'       => $guest->email,
                'status'      => strtoupper($guest->getStatus()),
                'created_at'  => $guest->created_at,
            ];
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
            'start_date'             => $carbonStartDate->format('Y-m-d'),
            'end_date'               => $carbonEndDate->format('Y-m-d'),
            'start_date_BR'          => $carbonEndDate->format('d/m/Y'),
            'end_date_BR'            => $carbonEndDate->format('d/m/Y'),
            'start_time'             => $carbonStartDate->format('H:i'),
            'end_time'               => $carbonEndDate->format('H:i'),
            'people_limit'           => $event->people_limit,
            'guest_limit_per_person' => $event->guest_limit_per_person,
            'value_per_person'       => $event->value_per_person,
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
            'longitude'              => $event->longitude
        ];

    }

}
