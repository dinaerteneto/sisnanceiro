<?php
namespace Sisnanceiro\Transformers;

use League\Fractal\TransformerAbstract;
use Sisnanceiro\Models\Event;

class EventGuestsTransform extends TransformerAbstract
{

    public function transform(Event $event)
    {
        $return = [];
        if ($guests = $event->guests()->orderBy('person_name')) {
            foreach ($guests->get() as $guest) {
                $return[] = [
                    'id'     => $guest->id,
                    'name'   => $guest->person_name,
                    'email'  => $guest->email,
                    'status' => $guest->getStatus(),
                ];
            }
            // $return['total_guest'] = count($guests);
        }
        return $return;
    }
}
