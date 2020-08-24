<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Sisnanceiro\Models\BankCategory;
use Sisnanceiro\Models\BankInvoiceDetail;
use Sisnanceiro\Models\CreditCard;
use Sisnanceiro\Services\BankCategoryService;
use Sisnanceiro\Services\BankTransactionService;
use Sisnanceiro\Transformers\BankCategoryTransformer;
use Sisnanceiro\Transformers\BankTransactionTotalTransformer;
use Sisnanceiro\Transformers\BankTransactionTransformer;

class CreditCardTransactionController extends Controller
{
    protected $bankTransactionService;

    public function __construct(
        BankTransactionService $bankTransactionService,
        BankCategoryService $bankCategoryService
    ) {
        $this->bankTransactionService = $bankTransactionService;
        $this->bankCategoryService    = $bankCategoryService;
    }

    public function index(Request $request, $id)
    {
        if ($request->isMethod('post')) {
            $records = $this->bankTransactionService->getAll($request->get('extra_search'));
            $dt      = datatables()
                ->of($records)
                ->setTransformer(new BankTransactionTransformer);
            return $dt->make(true);
        }

        $creditCards = CreditCard::all();
        $model       = CreditCard::find($id);
        $title       = $model->name;

        $dateStartDate = isset($request->start_date) && !empty($request->start_date) ? $request->start_date : date('Y-m') . "-{$model->closing_day}";
        $dateDueDate   = isset($request->end_date) && !empty($request->end_date) ? $request->end_date : date('Y-m') . "-{$model->payment_day}";

        $startDate       = Carbon::createFromFormat('Y-m-d', $dateStartDate);
        $dueDate         = Carbon::createFromFormat('Y-m-d', $dateDueDate);
        $previousEndDate = clone $dueDate;
        $nextEndDate     = clone $dueDate;

        if ($startDate->isPast()) {
            $dueDate = $dueDate->addMonth();
        }

        $currentDate = clone $startDate;
        $currentDate = $startDate->format('m/Y');

        $previousStartDate = clone $startDate;
        $previousStartDate = $previousStartDate->addMonth(-1)->format('Y-m-d');
        $previousEndDate   = $previousEndDate->addMonth(-1)->format('Y-m-d');

        $nextStartDate = clone $startDate;
        $nextStartDate = $nextStartDate->addMonth()->format('Y-m-d');
        $nextEndDate   = $nextEndDate->addMonth()->format('Y-m-d');

        $dueDate       = $dueDate->format('d/m/Y');
        $endDateFormat = clone $startDate;
        $endDateFormat = $endDateFormat->addMonth();
        $endDate       = $startDate->addMonth()->format('Y-m-d');
        $startDate     = $startDate->addMonth(-1)->format('Y-m-d');

        $status = 'Fatura fechada';
        if ($endDateFormat->isFuture()) {
            $status = 'Fatura aberta';
        }
        $endDateFormat = $endDateFormat->format('d/m/Y');

        return view('/credit-card/index-invoice', compact(
            'urlMain',
            'title',
            'creditCards',
            'model',
            'startDate',
            'endDate',
            'dueDate',
            'currentDate',
            'endDateFormat',
            'previousStartDate',
            'previousEndDate',
            'nextStartDate',
            'nextEndDate',
            'status'
        )
        );
    }

    public function create(Request $request, $id)
    {
        $title       = 'Incluir despesa de cartão';
        $model       = new BankInvoiceDetail();
        $creditCards = CreditCard::all();

        $mainCategoryId    = BankCategory::CATEGORY_TO_PAY;
        $categories        = $this->bankCategoryService->getAll($mainCategoryId);
        $categoryTransform = new BankCategoryTransformer();
        $categoryOptions   = json_encode($categoryTransform->buildHtmlDiv($categories->toArray(), $mainCategoryId));

        $action = "/credit-card/{$id}/create";

        if ($request->isMethod('post')) {
            $postData = $request->all();
            $model    = $this->bankTransactionService->store($postData, 'create');
            if (method_exists($model, 'getErrors') && $model->getErrors()) {
                $request->session()->flash('error', ['message' => 'Erro na tentativa de criar a transação.', 'errors' => $model->getErrors()]);
            } else {
                $request->session()->flash('success', ['message' => 'Transação criada com sucesso.']);
            }
            $urlMain = "/credit-card/{$id}";
            return redirect($urlMain);
        }
        return view('credit-card-transaction/_form', compact('model', 'creditCards', 'action', 'title', 'categoryOptions'));
    }

    public function update(Request $request, $credit_card_id, $id)
    {
        $title          = 'Alterar despesa de cartão';
        $creditCards    = CreditCard::all();
        $mainCategoryId = BankCategory::CATEGORY_TO_PAY;

        $action = "/credit-card/{$credit_card_id}/update/{$id}";
        $model  = $this->bankTransactionService->findByInvoice($id);

        $categories        = $this->bankCategoryService->getAll($mainCategoryId);
        $categoryTransform = new BankCategoryTransformer();
        $categoryOptions   = json_encode($categoryTransform->buildHtmlDiv($categories->toArray(), $mainCategoryId));

        if ($request->isMethod('post')) {
            $postData = $request->all();
            $option   = BankTransactionService::OPTION_ALL;

            $model = $this->bankTransactionService->updateInvoices($model, $postData, $option);
            if (method_exists($model, 'getErrors') && $model->getErrors()) {
                $request->session()->flash('error', ['message' => 'Erro na tentativa de alterar o lançamento.', 'errors' => $model->getErrors()]);
            } else {
                $request->session()->flash('success', ['message' => 'Lançamento(s) alterado(s) com sucesso.']);
            }
            return redirect("/credit-card/{$credit_card_id}");
        }

        $model = (object) fractal($model, new BankTransactionTransformer)->toArray()['data'];
        return view('credit-card-transaction/_form_update', compact('model', 'creditCards', 'action', 'title', 'categoryOptions'));
    }

    public function delete(Request $request, $credit_card_id, $id)
    {
        if ($request->isMethod('post')) {
            $option = BankTransactionService::OPTION_ALL;
            if ($this->bankTransactionService->destroyInvoices($id, $option)) {
                return $this->apiSuccess(['success' => true]);
            }
            return Response::json(['success' => false, 'message' => 'Erro na tentativa de excluir o(s) lançamentos.']);
        }
    }

    public function getTotal(Request $request)
    {
        $model    = (object) $this->bankTransactionService->getTotal($request->get('extra_search'));
        $response = fractal($model, new BankTransactionTotalTransformer)->toArray()['data'];
        return Response::json($response);
    }

}
