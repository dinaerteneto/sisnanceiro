<?php
namespace Sisnanceiro\Repositories;

use Sisnanceiro\Models\EventGuest;
use Illuminate\Support\Facades\DB;

class EventGuestRepository extends Repository
{

    public function __construct(EventGuest $model)
    {
        $this->model = $model;
    }

    public function allMainGuest($eventId) {
        return EventGuest::where('event_id', '=', $eventId)
            ->whereNull('invited_by_id')
            ->get();
    }

    /**
     * return all guests
     * @param integer $eventId
     * @return Collection
     */
    public function allGuests($eventId, $guestId = null)
    {
        $guests = EventGuest::select('*')
            ->where('event_id', '=', $eventId);
        if ($guestId) {
            $guests->where('invited_by_id', '=', $guestId);
        }
        if($guests) {
            return $guests->get();
        }
        return [];
    }       
}
