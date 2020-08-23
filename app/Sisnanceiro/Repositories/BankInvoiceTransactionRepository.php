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
            , credit_card.id AS credit_card_id
            , credit_card.name AS credit_card_name
            , bank_account.name AS bank_account_name
            , bank_account_source.name AS bank_account_source_name
            , bank_account_target.name AS bank_account_target_name
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
            ->leftJoin('bank_account', 'bank_account.id', '=', 'bank_invoice_detail.bank_account_id')
            ->leftJoin('person as person_customer', \DB::raw('person_customer.id'), '=', 'bank_invoice_detail.customer_id')
            ->leftJoin('person as person_supplier', \DB::raw('person_supplier.id'), '=', 'bank_invoice_detail.supplier_id')
            ->leftJoin('bank_account as bank_account_source', \DB::raw('bank_account_source.id'), '=', 'bank_invoice_transaction.bank_account_source_id')
            ->leftJoin('bank_account as bank_account_target', \DB::raw('bank_account_target.id'), '=', 'bank_invoice_transaction.bank_account_target_id')
            ->leftJoin('credit_card', 'credit_card.id', '=', 'bank_invoice_detail.credit_card_id')
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
            $query = $query->orWhere('description', 'like', "%{$search['description']}%");
        }
        if (isset($search['bank_categories_ids']) && !empty($search['bank_categories_ids'])) {
            $query = $query->whereIn('bank_category.id', $search['bank_categories_ids']);
        }
        if (isset($search['credit_card_id']) && !empty($search['credit_card_id'])) {
            $query = $query->where('credit_card.id', '=', $search['credit_card_id']);
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
        if (isset($serch['credit_card_id']) && !empty($search['credit_card_id'])) {
            $query = $query->whereIn('credit_card_id', $search['credit_card_id']);
        }

        return $query->get();
    }

}
