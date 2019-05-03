<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use Sisnanceiro\Services\EventGuestService;
use Sisnanceiro\Transformers\EventGuestTransform;

class EventGuestController extends Controller
{
    protected $eventGuestService;

    public function __construct(EventGuestService $eventGuestService)
    {
        $this->eventGuestService = $eventGuestService;
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

}
