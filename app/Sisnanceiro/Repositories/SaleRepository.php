<?php
namespace Sisnanceiro\Repositories;

use Sisnanceiro\Models\Sale;
use Illuminate\Support\Facades\Auth;

class SaleRepository extends Repository
{
    public function __construct(Sale $model)
    {
        $this->model = $model;
    }

    /**
     * get all customers
     * @param String $search
     * @return Illuminate\Database\Query\Builder
     */
    public function getAll($search = null)
    {
        $companyId = Auth::user()->company_id;
        $query     = \DB::query()
            ->selectRaw("
                  sale.id
                , sale.company_sale_code
                , sale.gross_value
                , sale.net_value
                , DATE_FORMAT(sale.sale_date, '%d/%m/%Y') AS sale_date
                , customer.firstname AS customer_firstname
                , customer.lastname AS customer_lastname
                , user_created.firstname AS user_created_firstname
                , user_created.lastname AS user_created_lastname
                , CASE sale.status
                  WHEN 1 THEN 'Finalizada'
                  WHEN 2 THEN 'Cancelada'
                  WHEN 3 THEN 'Em andamento'
                  END status
            ")
            ->from('sale')
            ->join('person AS customer', \DB::raw('customer.id'), '=', \DB::raw('sale.customer_id'))
            ->join('person AS user_created', \DB::raw('user_created.id'), '=', \DB::raw('sale.user_id_created'))
            ->where('sale.company_id', '=', $companyId)
            ->whereNull('sale.deleted_at');
         if (!empty($search)) {
            $query->where(\DB::raw("customer_firstname LIKE '%{$search}%'"))
                ->orWhere(\DB::raw("customer_lastname LIKE '%{$search}%'"))
                ->orWhere(\DB::raw("user_created LIKE '%{$search}%'"))
                ->orWhere(\DB::raw("user_created LIKE '%{$search}%'"));
        } 
        return $query;

    }
}
