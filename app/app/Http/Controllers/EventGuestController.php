<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Sisnanceiro\Models\PaymentMethod;
use Sisnanceiro\Services\EventGuestService;
use Sisnanceiro\Services\EventService;
use Sisnanceiro\Transformers\EventGuestTransformer;

class EventGuestController extends Controller
{
    protected $eventGuestService;
    protected $eventService;

    public function __construct(EventGuestService $eventGuestService, EventService $eventService)
    {
        $this->eventGuestService = $eventGuestService;
        $this->eventService      = $eventService;
    }

    public function index(Request $request, $tokenEmail)
    {
        $guest       = $this->eventGuestService->findBy('token_email', $tokenEmail);
        $transform   = fractal($guest, new EventGuestTransformer());
        $data        = $transform->toArray()['data'];
        $invitedByMe = $data['invitedByMe'];
        $event       = $data['event'];
        return view('event-guest/index', compact('data', 'invitedByMe', 'event'));
    }

    public function sendInvite(Request $request, $tokenEmail)
    {
        $return = ['success' => false];
        $data   = $request->get('EventGuest');
        $return = $this->eventGuestService->addGuest($data['event_id'], $data);
        return Response::json($return);
    }

    public function changeStatus($tokenEmail, $status)
    {
        $guest = $this->eventGuestService->findBy('token_email', $tokenEmail);
        if (empty($guest->responsable_of_payment) && $guest->value > 0) {
            return 'Status não pode ser alterado.';
        }
        $record           = $guest->toArray();
        $record['status'] = $status;
        $this->eventGuestService->update($guest, $record);
        $transform = fractal($guest, new EventGuestTransformer());
        $data      = $transform->toArray()['data'];
        return View('event-guest/_status', compact('data'));
    }

    public function invoice($tokenEmail)
    {
        echo $tokenEmail;
        exit;
    }

    public function paymentWithMoney(Request $request, $tokenEmail)
    {
        $guest = $this->eventGuestService->findBy('token_email', $tokenEmail);
        $record = $guest->toArray();
        $record['payment_method_id'] = PaymentMethod::MONEY;
        if ($this->eventGuestService->update($guest, $record)) {
            $request->session()->flash('success', ['message' => 'Forma de pagto alterada com sucesso.']);
        } else {
            $request->session()->flash('error', ['message' => 'Falha na tentativa de alterar a forma de pagto.']);
        }
        return redirect("/guest/{$tokenEmail}");
    }
}
