<?php
namespace App\Http\Controllers;

use Sisnanceiro\Services\CreditCardService;

class CronController extends Controller
{
    protected $creditCardService;

    public function __construct(
        CreditCardService $creditCardService
    ) {
        $this->creditCardService = $creditCardService;
    }

    public function creditCardInvoices()
    {
        $date = date('Y-m-d');
        $this->creditCardService->closeInvoice($date);
    }
}
