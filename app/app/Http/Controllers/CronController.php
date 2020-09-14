<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Response;
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
  $return = $this->creditCardService->closeInvoice();
  return Response::json($return);
 }
}
