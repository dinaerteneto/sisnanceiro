<?php
namespace Sisnanceiro\Repositories;

use Illuminate\Support\Facades\Auth;
use Sisnanceiro\Models\BankInvoiceDetail;
use Sisnanceiro\Models\BankInvoiceTransaction;

class BankInvoiceTransactionRepository extends Repository
{
    public function __construct(
        BankInvoiceTransaction $model,
        BankInvoiceDetail $bankInvoiceDetail
    ) {
        $this->model             = $model;
        $this->bankInvoiceDetail = $bankInvoiceDetail;
    }

/*     public function find($id) {
return $this->bankInvoiceDetail->find($id);
}
 */

    
    public function getAll(array $search = [])
    {
        $companyId = Auth::user()->company_id;

        $query = \DB::table('bank_invoice_detail')
            ->selectRaw(
                ' bank_invoice_detail.id
            , bank_invoice_detail.bank_invoice_transaction_id
            , bank_invoice_detail.supplier_id
            , bank_invoice_detail.customer_id
            , bank_invoice_detail.bank_account_id
            , bank_invoice_detail.bank_category_id
            , bank_category.main_parent_category_id
            , due_date
            , bank_account.name AS bank_account_name
            , bank_category.name AS bank_category_name
            , person_customer.firstname AS customer_firstname
            , person_customer.lastname AS customer_lastname
            , person_supplier.firstname AS supplier_firstname
            , person_supplier.lastname AS supplier_lastname
            , note
            , description
            , net_value
            , gross_value
            , total_invoices
            , parcel_number
            , CASE WHEN bank_invoice_detail.status = 1
               AND due_date > CURDATE()
              THEN 2
              ELSE bank_invoice_detail.status
               END status
            '
            )
            ->join('bank_invoice_transaction', 'bank_invoice_transaction.id', '=', 'bank_invoice_detail.bank_invoice_transaction_id')
            ->join('bank_category', 'bank_category.id', '=', 'bank_invoice_detail.bank_category_id')
            ->join('bank_account', 'bank_account.id', '=', 'bank_invoice_detail.bank_account_id')
            ->leftJoin('person as person_customer', \DB::raw('person_customer.id'), '=', 'bank_invoice_detail.customer_id')
            ->leftJoin('person as person_supplier', \DB::raw('person_supplier.id'), '=', 'bank_invoice_detail.supplier_id')
            ->where('bank_invoice_detail.company_id', '=', $companyId)
            ->whereNull('bank_invoice_detail.deleted_at');

        if (isset($search['start_date']) && !empty($search['start_date'])) {
            $query = $query->whereBetween('due_date', [$search['start_date'], $search['end_date']]);
        }
        if (isset($search['main_parent_category_id']) && !empty($search['main_parent_category_id'])) {
            $query = $query->where('bank_category.main_parent_category_id', '=', $search['main_parent_category_id']);
        }
        if (isset($search['bank_account_id']) && !empty($search['bank_account_id'])) {
            $query = $query->whereIn('bank_account_id', $search['bank_account_id']);
        }
        if (isset($search['status']) && !empty($search['status'])) {
            $query = $query->whereIn('bank_invoice_detail.status', $search['status']);
        }
        if (isset($search['description']) && !empty($search['description'])) {
            $query = $query->where('note', 'like', "%{$search['description']}%");
        }
        return $query->get();
    }

    /**
     * return total values (to_receive and to_pay) based filters
     * @param array $search params for search
     * @return array
     */
    public function getTotalByMainCategory(array $search = [])
    {
        $companyId = Auth::user()->company_id;

        $query = \DB::table('bank_invoice_detail')
            ->selectRaw('bank_category.main_parent_category_id, sum(bank_invoice_detail.net_value) as total')
            ->join('bank_category', 'bank_category.id', '=', 'bank_invoice_detail.bank_category_id')
            ->where('bank_invoice_detail.company_id', '=', $companyId)
            ->whereNull('bank_invoice_detail.deleted_at')
            ->groupBy('bank_category.main_parent_category_id');

        if (isset($search['start_date']) && !empty($search['start_date'])) {
            $query = $query->whereBetween('due_date', [$search['start_date'], $search['end_date']]);
        }
        if (isset($search['main_parent_category_id']) && !empty($search['main_parent_category_id'])) {
            $query = $query->where('bank_category.main_parent_category_id', '=', $search['main_parent_category_id']);
        }
        if (isset($search['bank_account_id']) && !empty($search['bank_account_id'])) {
            $query = $query->whereIn('bank_account_id', $search['bank_account_id']);
        }
        if (isset($search['status']) && !empty($search['status'])) {
            $query = $query->whereIn('bank_invoice_detail.status', $search['status']);
        }
        if (isset($search['description']) && !empty($search['description'])) {
            $query = $query->where('note', 'like', "%{$search['description']}%");
        }
        return $query->get();
    }

}
