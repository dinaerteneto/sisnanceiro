<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Sisnanceiro\Services\ReportService;
use Sisnanceiro\Transformers\CashFlowDetailTransformer;
use Sisnanceiro\Transformers\CashFlowTransformer;

class ReportController extends Controller {

	public function __construct(ReportService $service) {
		$this->service = $service;
	}

	public function cashFlow(Request $request) {
		if ($request->isMethod('post')) {
			$records = $this->service->cashFlowPastAndFuture('2020-07-01', '2020-07-31', [13, 14, 15]);
			$dt = datatables()
				->of($records)
				->setTransformer(new CashFlowTransformer);
			return $dt->make(true);
		}
		return view('/reports/cash-flow');
	}

	public function cashFlowDetail(Request $request) {
		$date = $request->date;
		$records = $this->service->cashFlowPast($date, $date, [13, 14, 15], 'day');
		$data = (object) fractal($records, new CashFlowDetailTransformer)->toArray()['data'];
		return view('/reports/cash-flow-detail', compact('data'));
	}

}
