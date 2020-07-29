<?php

namespace Sisnanceiro\Services;

use Sisnanceiro\Repositories\CashFlowRepository;

class ReportService extends Service {

	public function __construct(CashFlowRepository $cashFlowrepository) {
		$this->cashFlowRepository = $cashFlowrepository;
	}

	public function cashFlowPast($periodFrom, $periodTo, $bankAccountId = [], $groupBy = 'date') {
		return $this->cashFlowRepository->past($periodFrom, $periodTo, $bankAccountId, $groupBy);
	}

	public function cashFlowPastAndFuture($periodFrom, $periodTo, $bankAccountId = []) {
		return $this->cashFlowRepository->pastAndFuture($periodFrom, $periodTo, $bankAccountId);
	}

	public function cashFlowFuture($periodFrom, $periodTo, $bankAccountId = [], $groupBy = 'date') {
		return $this->cashFlowRepository->future($periodFrom, $periodTo, $bankAccountId, $groupBy);
	}

}
