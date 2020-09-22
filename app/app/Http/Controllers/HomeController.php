<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Sisnanceiro\Helpers\Mask;
use Sisnanceiro\Models\BankCategory;
use Sisnanceiro\Services\CompanyService;
use Sisnanceiro\Services\DashboardService;
use Sisnanceiro\Transformers\DashboardCalendarTransformer;

class HomeController extends Controller
{
 protected $companyService;
 /**
  * Create a new controller instance.
  *
  * @return void
  */
 public function __construct(CompanyService $companyService, DashboardService $dashboardService)
 {
  $this->companyService   = $companyService;
  $this->dashboardService = $dashboardService;
  // $this->middleware('auth');
 }

 public function register(Request $request)
 {
  if ($request->isMethod('post')) {
   $model    = $this->companyService->register($request->get('Register'));
   $postData = $request->get('Register');

   if (method_exists($model, 'getErrors') && $model->getErrors()) {
    $request->session()->flash('error', ['message' => 'Erro na tentativa de criar seu cadastro.', 'errors' => $model->getErrors()]);
   } else {
    $msgSuccess = "Olá {$postData['firstname']}, bem vindo ao SiSnanceiro.<br> Uma senha foi criada e enviada para seu e-mail: <strong>{$postData['email']}</strong>";
    $msgSuccess .= "<br>";
    $msgSuccess .= "Acesse seu e-mail para obter a senha.";
    $request->session()->flash('success', ['message' => $msgSuccess]);
   }
   return redirect('/');
  }
 }

 public function home()
 {
  $aBallance = $this->dashboardService->ballance();

  $aBallance = [
   'bank_account' => Mask::currency($aBallance['bankAccount']->ballance),
   'to_receive'   => Mask::currency($aBallance['toReceive']->ballance),
   'to_pay'       => Mask::currency($aBallance['toPay']->ballance),
   'credit_card'  => Mask::currency($aBallance['creditCard']->ballance),
  ];

  $jsonLabel        = '';
  $jsonValue        = '';
  $aTotalByCategory = $this->dashboardService->totalByCategory([BankCategory::CATEGORY_TO_PAY], '2020-08-01', '2020-08-31');
  if ($aTotalByCategory) {
   $jsonLabel = json_encode(array_map(function ($data) {return $data->name;}, $aTotalByCategory));
   $jsonValue = json_encode(array_map(function ($data) {return $data->total * -1;}, $aTotalByCategory));
  }

  $aTotalByParentCategory = $this->dashboardService->totalByParentCategory([BankCategory::CATEGORY_TO_PAY], '2020-08-01', '2020-08-31');
  if ($aTotalByParentCategory) {
   $jsonParentLabel = json_encode(array_map(function ($data) {return $data->name;}, $aTotalByParentCategory));
   $jsonParentValue = json_encode(array_map(function ($data) {return $data->total * -1;}, $aTotalByParentCategory));
  }

  return view('home', compact('aBallance', 'jsonLabel', 'jsonValue', 'jsonParentLabel', 'jsonParentValue'));
 }

 public function calendar(Request $request)
 {
  $start = $request->get('start');
  $end   = $request->get('end');

  if ($calendars = $this->dashboardService->calendar($start, $end)) {
   $calendarTransformers = fractal($calendars, new DashboardCalendarTransformer());
   return Response::json($calendarTransformers);
  }
  return Response::json([]);
 }
}
