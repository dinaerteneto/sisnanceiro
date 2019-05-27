<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Sisnanceiro\Models\Event;
use Sisnanceiro\Services\EventGuestService;
use Sisnanceiro\Services\EventService;
use Sisnanceiro\Transformers\EventGuestsTransform;
use Sisnanceiro\Transformers\EventGuestTransform;
use Sisnanceiro\Transformers\EventTransform;

class EventController extends Controller
{
    private $eventService;
    private $eventGuestService;

    public function __construct(EventService $eventService, EventGuestService $eventGuestService)
    {
        $this->eventService      = $eventService;
        $this->eventGuestService = $eventGuestService;
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
        $model        = $this->eventService->find($id);
        $action       = "/event/update/{$id}";
        $title        = "Evento {$model->name}";
        $urlStartDate = null;

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
            $urlStartDate       = $carbonStartDate->format('d-m-Y');

            return view('event/_form', compact('model', 'action', 'title', 'urlStartDate'));
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

    public function guest($eventId)
    {
        $guests         = [];
        $model          = $this->eventService->find($eventId);
        $eventTransform = fractal($model, new EventTransform());
        $model          = (object) $eventTransform->toArray()['data'];

        if ($eventGuest = $this->eventGuestService->all($eventId)) {
            $eventGuestTransform = new EventGuestsTransform();
            $guests              = $eventGuestTransform->buildTree($eventGuest->toArray());
        }
        return View('/event/guest', compact('model', 'guests'));
    }

    public function addGuest(Request $request, $eventId)
    {
        $newId = 'New-' . $request->get('newId');
        return View('event/_form_guest', compact('newId'));
    }

    public function storeGuest(Request $request, $eventId)
    {
        $return   = [];
        $postData = $request->get('Guest');
        if ($postData) {
            foreach ($postData as $data) {
                $guest = $this->eventGuestService->addGuest($eventId, $data);
                if (!$guest['success'] && isset($guest['message'])) {
                    $request->session()->flash('error', ['message' => $guest['message'], 'errors' => []]);
                }
                $return[] = $guest;
            }
        }
        return Response::json($return);
    }

    public function guests($eventId)
    {
        $model     = $this->eventGuestService->confirmed($eventId);
        $transform = fractal($model, new EventGuestTransform());
        $data      = $transform->toArray()['data'];
        return View('event/_guests', compact('data'));
    }

    public function actions(Request $request, $eventId)
    {
        $return['success'] = false;
        $postData          = $request->get('EventGuest');
        $status            = $postData['status'];
        $ids               = [];
        if ($postData) {
            foreach ($postData['ids'] as $key => $id) {
                $ids[] = $id;
            }
        }

        switch ($status) {
            case '1':
            case '2':
            case '3':
                if ($this->eventGuestService->updateStatus($ids, $status)) {
                    $return['success'] = true;
                }
                break;
            case 're-send-mail':
                foreach ($ids as $id) {
                    $eventGuest = $this->eventGuestService->find($id);
                    $this->eventGuestService->sendInvoiceToMail($eventGuest);
                }
                $return['success'] = true;
                break;
        }

        return Response::json($return);
    }

    public function page(Request $request, $eventId)
    {
        $model     = $this->eventService->find($eventId);
        $transform = fractal($model, new EventTransform());
        $data      = $transform->toArray()['data'];

        if ($request->isMethod('post')) {
            $return = [
                'success' => false,
                'message' => 'Erro na tentativa de inserir os dados',
            ];
            if ($this->eventGuestService->addOrUpdate($model, $request->get('EventGuest'))) {
                $return = [
                    'success' => true,
                    'message' => 'Seus dados foram incluído com sucesso.',
                ];
            }
            return Response::json($return);
        } else {
            return View('event/_page', compact('data'));
        }
    }

}
