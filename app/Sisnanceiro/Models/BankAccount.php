<?php

namespace Sisnanceiro\Models;

use App\Scopes\TenantModels;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Sisnanceiro\Models\BankInvoiceDetail;

class BankAccount extends Model
{
 use TenantModels;
 use SoftDeletes;

 const CONTA_CORRENTE          = 'conta_corrente';
 const CONTA_POUPANCA          = 'conta_poupanca';
 const CONTA_CORRENTE_CONJUNTA = 'conta_corrente_conjunta';
 const CONTA_POUPANCA_CONJUNTA = 'conta_poupanca_conjunta';

 protected $table      = 'bank_account';
 protected $primaryKey = 'id';

 protected $fillable = [
  'company_id',
  'bank_id',
  'default',
  'default_online',
  'physical',
  'name',
  'bank',
  'agency',
  'account',
  'type',
  'initial_balance',
  'initial_balance_date',
  'agency_dv',
  'account_dv',
  'cpf_cnpj',
  'legal_name',
 ];

 public static function getType($type)
 {
  $ret = null;
  switch ($type) {
   case self::CONTA_CORRENTE:
    $ret = 'Conta corrente';
    break;

   case self::CONTA_POUPANCA:
    $ret = 'Conta poupança';
    break;

   case self::CONTA_CORRENTE_CONJUNTA:
    $ret = 'Conta corrente conjunta';
    break;

   case self::CONTA_POUPANCA_CONJUNTA:
    $ret = 'Conta poupança conjunta';
    break;
  }
  return $ret;
 }

 public static function getTypes()
 {
  return [
   1 => 'Conta corrente',
   2 => 'Conta poupança',
   3 => 'Conta corrente conjunta',
   4 => 'Conta poupança conjunta',
  ];
 }

 public function getCurrentBalance()
 {
  $balance = BankInvoiceDetail::select(\DB::raw('SUM(net_value) AS current_balance'))
//   ->where('due_date', '<=', date('Y-m-d'))
   ->where('status', BankInvoiceDetail::STATUS_PAID)
   ->where('bank_account_id', $this->id)
   ->whereNull('credit_card_id')
   ->first();
  return $balance ? $balance->current_balance : 0;
 }

}
