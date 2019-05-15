<?php

namespace Sisnanceiro\Transformers;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use League\Fractal\TransformerAbstract;
use Sisnanceiro\Helpers\Mask;
use Sisnanceiro\Models\Event;
use Sisnanceiro\Models\EventGuest;

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
            'mainGuests'             => $this->mainGuest($event->mainGuests()->get()),
            'totalGuest'             => $this->totalGuest($event),
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
                'invitedByMe' => $this->mainGuest($guest->invitedByMe()->get()),
            ];
        }
        return $data;
    }

    /**
     * return total guest by status
     * @param Event $event
     * @return array
     */
    private function totalGuest(Event $event)
    {
        $guests    = $event->guests()->get();
        $total     = count($guests);
        $waiting   = 0;
        $confirmed = 0;
        $denied    = 0;
        $revenue   = 0;
        if ($guests) {
            foreach ($guests as $guest) {
                switch ($guest->status) {
                    case EventGuest::STATUS_WAITING:
                        $waiting++;
                        break;
                    case EventGuest::STATUS_CONFIRMED:
                        $confirmed++;
                        $revenue += $event->value_per_person;
                        break;
                    case EventGuest::STATUS_DENIED:
                        $denied++;
                        break;
                }
            }
        }
        return [
            'total'     => $total,
            'confirmed' => $confirmed,
            'waiting'   => $waiting,
            'denied'    => $denied,
            'revenue'   => Mask::currency($revenue),
        ];
    }

}
