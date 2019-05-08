<?php

namespace Sisnanceiro\Transformers;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use League\Fractal\TransformerAbstract;
use Sisnanceiro\Models\Event;

class EventTransform extends TransformerAbstract
{

    public function transform(Event $event)
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
            'longitude'              => $event->longitude,
            'mainGuests'             => $this->mainGuest($event->mainGuests()->get()),
        ];
    }

    private function mainGuest(Collection $guests)
    {
        $data = [];
        foreach ($guests as $guest) {
            $data[] = [
                'id'          => $guest->id,
                'person_name' => $guest->person_name,
                'email'       => $guest->email,
                'status'      => strtoupper($guest->getStatus()),
                'status_int'  => (int) $guest->status,
                'created_at'  => $guest->created_at,
                'invitedByMe' => $this->mainGuest($guest->invitedByMe()->get())
            ];
        }
        return $data;
    }

}
