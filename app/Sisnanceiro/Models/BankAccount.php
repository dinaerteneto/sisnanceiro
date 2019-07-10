<?php

namespace Sisnanceiro\Models;

use App\Scopes\TenantModels;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
}
