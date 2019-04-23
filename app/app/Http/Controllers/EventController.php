<?php

namespace App\Http\Controllers;

use Sisnanceiro\Services\EventService;

class EventController extends Controller
{
    private $eventService;

    public function __construct(EventService $eventService)
    {
        $this->eventService = $eventService;
    }

    public function index()
    {
        return view('event/index');
    }

}
