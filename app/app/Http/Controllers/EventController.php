<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Sisnanceiro\Models\Event;
use Sisnanceiro\Services\EventService;
use Sisnanceiro\Transformers\EventTransform;

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
        $date   = $request->has('date') ? $request->get('date') : null;

        $model = new Event();
        if (!empty($date)) {
            $model->start_date = $date;
            $model->end_date   = $date;
        }

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

        return view('event/_form', compact('model', 'action', 'title', 'date'));
    }

    public function update(Request $request, $id)
    {
        $model  = $this->eventService->find($id);
        $action = "/event/update/{$id}";
        $title  = "Evento {$model->name}";

        if ($request->isMethod('post')) {
            $data  = $request->get('Event');
            $model = $this->eventService->update($model, $data, 'update');
            if (method_exists($model, 'getErrors') && $model->getErrors()) {
                $request->session()->flash('error', ['message' => 'Erro na tentativa de alterar o evento.', 'errors' => $model->getErrors()]);
            } else {
                $request->session()->flash('success', ['message' => 'Evento alterado com sucesso.']);
            }
            return redirect('event/');
        } else {
            $carbonStartDate    = Carbon::createFromFormat('Y-m-d H:i:s', $model->start_date);
            $carbonEndDate      = Carbon::createFromFormat('Y-m-d H:i:s', $model->end_date);
            $model->start_date  = $carbonStartDate->format('d/m/Y');
            $model->end_date    = $carbonEndDate->format('d/m/Y');
            $model->start_time  = $carbonStartDate->format('H:i');
            $model->end_time    = $carbonEndDate->format('H:i');
            $model->description = strip_tags($model->description);

            return view('event/_form', compact('model', 'action', 'title'));
        }

    }

    public function load(Request $request)
    {
        $start = $request->get('start');
        $end   = $request->get('end');

        if ($events = $this->eventService->load($start, $end)) {
            $eventTransform = fractal($events, new EventTransform());
            return Response::json($eventTransform);
        }
        return Response::json([]);
    }

    public function delete($id)
    {
        if ($this->eventService->destroy($id)) {
            return $this->apiSuccess(['success' => true]);
        }
    }

    public function guest(Request $request, $eventId)
    {
        $model          = $this->eventService->find($eventId);
        $eventTransform = fractal($model, new EventTransform());
        $model          = (object) $eventTransform->toArray()['data'];

        return View('/event/guest', compact('model'));
    }

    public function addGuest(Request $request, $eventId)
    {
        $newId = 'New-' . $request->get('newId');
        return View('event/_form_guest', compact('newId'));
    }

    public function storeGuest(Request $request, $eventId)
    {
        $data = $request->get('Guest');
        if ($this->eventService->addGuest($eventId, $data)) {
            return Response::json(['success' => true]);
        }
        return Response::json(['success' => false]);
    }

}
