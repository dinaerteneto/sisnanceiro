<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Sisnanceiro\Models\Event;
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

    public function create(Request $request)
    {
        $action = "/event/create";
        $title  = 'Incluir evento';
        $model  = new Event();

        if ($request->isMethod('post')) {
            $data  = $request->get('Event');
            $model = $this->eventService->store($data, 'create');
            if (method_exists($model, 'getErrors') && $model->getErrors()) {
                $request->session()->flash('error', ['message' => 'Erro na tentativa de incluir o evento.', 'errors' => $model->getErrors()]);
            } else {
                $request->session()->flash('success', ['message' => 'Evento incluído com sucesso.']);
            }
            return redirect('event/');
        }

        return view('event/_form', compact('model', 'action', 'title'));
    }

}
