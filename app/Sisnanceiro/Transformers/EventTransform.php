<?php

namespace Sisnanceiro\Transformers;

use Carbon\Carbon;
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
            'start_date'             => $carbonStartDate->format('Y-m-d H:i'),
            'end_date'               => $carbonEndDate->format('Y-m-d H:i'),
            'people_limit'           => $event->people_limit,
            'guest_limit_per_person' => $event->guest_limit_per_person,
            'value_per_person'       => $event->value_per_person,
            'description'            => $event->description,
            'zipcode'                => $event->zipcode,
            'address'                => $event->address,
            'address_number'         => $event->address_number,
            'city'                   => $event->city,
            'district'               => $event->district,
            'complement'             => $event->complement,
            'reference'              => $event->reference,
            'latitude'               => $event->latitude,
            'longitude'              => $event->longitude,
        ];
    }

}
