<?php

namespace Sisnanceiro\Services;

use Sisnanceiro\Repositories\CashFlowRepository;

class ReportService extends Service
{

 public function __construct(CashFlowRepository $cashFlowrepository)
 {
  $this->cashFlowRepository = $cashFlowrepository;
 }

 public function cashFlowPast($periodFrom, $periodTo, $bankAccountId = [])
 {
  return $this->cashFlowRepository->past($periodFrom, $periodTo, $bankAccountId);
 }

 public function cashFlowPastAndFuture($periodFrom, $periodTo, $bankAccountId = [])
 {
  return $this->cashFlowRepository->pastAndFuture($periodFrom, $periodTo, $bankAccountId);
 }

 public function cashFlowFuture($periodFrom, $periodTo, $bankAccountId = [])
 {
  return $this->cashFlowRepository->future($periodFrom, $periodTo, $bankAccountId);
 }

}
