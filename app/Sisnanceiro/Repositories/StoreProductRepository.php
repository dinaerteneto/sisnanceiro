<?php
namespace Sisnanceiro\Repositories;

use Illuminate\Support\Facades\Auth;
use Sisnanceiro\Models\StoreProduct;

class StoreProductRepository extends Repository
{
    public function __construct(StoreProduct $model)
    {
        $this->model = $model;
    }

    /**
     * get all products
     * @param String $search
     * @return IIlluminate\Database\Query\Builder
     */
    public function getAll($search = null)
    {
        $companyId = Auth::user()->company_id;
        $queryBase = \DB::query()
            ->selectRaw("
                   `store_product`.`id`
                 , `store_product`.`store_product_id`
                 , `store_product_brand`.`name` AS `brand_name`
                 , `store_product_category`.`name` AS `category_name`
                 , `store_product`.`name`
                 , `store_product`.`price`
                 , `store_product`.`sku`
                 , `store_product`.`total_in_stock`
                 , `store_product`.`sale_with_negative_stock`
                 , `store_product`.`unit_measurement`
                 , `store_product_has_store_product_attribute`.`store_product_attribute_id`
                 , `store_product_has_store_product_attribute`.`value`
            ")
            ->from('store_product')
            ->leftJoin('store_product_brand', 'store_product.store_product_brand_id', '=', 'store_product_brand.id')
            ->leftJoin('store_product_category', 'store_product.store_product_category_id', '=', 'store_product_category.id')
            ->leftJoin('store_product_has_store_product_attribute', 'store_product_has_store_product_attribute.store_product_id', '=', 'store_product.id')
            ->whereRaw("store_product.company_id = $companyId");
            if (!empty($search)) {
                $queryBase->whereRaw("store_product.name LIKE '%{$search}%'");
            }
            $queryBase->whereNull('store_product.deleted_at');
            $queryBase = $queryBase->toSql();

        $query = \DB::table(\DB::raw('(' . $queryBase . ')  AS BASE'))
            ->select(\DB::raw("*, GROUP_CONCAT( value ORDER BY store_product_attribute_id SEPARATOR ' , ') AS attributes"))
            ->groupBy('id');
        return $query;
    }

}
