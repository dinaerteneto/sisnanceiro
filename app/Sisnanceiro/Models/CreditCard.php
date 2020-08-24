<?php

namespace Sisnanceiro\Models;

use App\Scopes\TenantModels;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Sisnanceiro\Models\BankCategory;

class CreditCard extends Model
{
    use TenantModels;
    use SoftDeletes;

    protected $table      = 'credit_card';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'company_id',
        'bank_account_id',
        'credit_card_brand_id',
        'name',
        'limit',
        'payment_day',
        'closing_day',
    ];

    public function bankAccount()
    {
        return $this->hasOne('Sisnanceiro\Models\BankAccount', 'id');
    }

    public function CreditCardBrand()
    {
        return $this->hasOne('Sisnanceiro\Models\CreditCardBrand', 'id');
    }

    public function getTotal($id, $startDate, $finalDate)
    {
        $companyId = Auth::user()->company_id;
        $query     = \DB::table('bank_invoice_detail')
            ->selectRaw('bank_category.main_parent_category_id, sum(bank_invoice_detail.net_value) as total')
            ->join('bank_category', 'bank_category.id', '=', 'bank_invoice_detail.bank_category_id')
            ->where('bank_invoice_detail.company_id', '=', $companyId)
            ->whereNull('bank_invoice_detail.deleted_at')
            ->groupBy('bank_category.main_parent_category_id')
            ->whereBetween('due_date', [$startDate, $finalDate])
            ->where('bank_category.main_parent_category_id', '=', BankCategory::CATEGORY_TO_PAY)
            ->where('bank_invoice_detail.credit_card_id', '=', $id)
            ->get()
            ->first();
        return $query;
    }

}
