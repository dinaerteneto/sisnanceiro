<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Sisnanceiro\Services\EventGuestService;
use Sisnanceiro\Services\EventService;

class EventGuestController extends Controller
{
    protected $eventGuestService;
    protected $eventService;

    public function __construct(EventGuestService $eventGuestService, EventService $eventService)
    {
        $this->eventGuestService = $eventGuestService;
        $this->eventService      = $eventService;
    }

    public function index(Request $request, $guestId)
    {
        $guest       = $this->eventGuestService->findBy('id', $guestId);
        $transform   = fractal($guest, new EventGuestTransform());
        $data        = $transform->toArray()['data'];
        $invitedByMe = $data['invitedByMe'];
        $event       = $data['event'];
        return view('event-guest/index', compact('data', 'invitedByMe', 'event'));
    }

    public function sendInvite(Request $request, $guestId)
    {
        $return = ['success' => false];
        $data[] = $request->get('EventGuest');
        if ($guests = $this->eventService->addGuest($data[0]['event_id'], $data)) {
            $guest     = $guests[0];
            $transform = fractal($guest, new EventGuestTransform());
            $return    = array_merge($transform->toArray()['data'], ['success' => true]);
        }
        return Response::json($return);
    }

    public function changeStatus($guestId, $status)
    {
        $guest            = $this->eventGuestService->findBy('id', $guestId);
        $record           = $guest->toArray();
        $record['status'] = $status;
        $this->eventGuestService->update($guest, $record);
        $transform = fractal($guest, new EventGuestTransform());
        $data      = $transform->toArray()['data'];
        return View('event-guest/_status', compact('data'));
    }
}
