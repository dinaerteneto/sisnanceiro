<?php

namespace App\Http\Controllers;

use Sisnanceiro\Services\EventService;
use Sisnanceiro\Models\Event;

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

    public function create() 
    {
        $action = "/event/create";
        $title  = 'Incluir evento';
        $model = new Event();

        return view('event/_form', compact('model', 'action', 'title'));
    }

}
