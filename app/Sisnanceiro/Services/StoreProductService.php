<?php

namespace Sisnanceiro\Services;

use Sisnanceiro\Helpers\FloatConversor;
use Sisnanceiro\Helpers\Validator;
use Sisnanceiro\Models\StoreProduct;
use Sisnanceiro\Repositories\StoreProductRepository;
use Sisnanceiro\Services\StoreProductHasStoreProductAttributeService;

class StoreProductService extends Service
{
    protected $rules = [
        'create' => [
            'name'                      => 'required|max:255',
            'store_product_category_id' => 'required|int',
            'store_product_brand_id'    => 'required|int',
            'status'                    => 'required',
            'sale_with_negative_stock'  => 'required',
            'price'                     => 'required|numeric',
        ],
        'update' => [
            'name'                     => 'required|max:255',
            // 'store_product_category_id' => 'required|int',
            // 'store_product_brand_id'    => 'required|int',
            'status'                   => 'required',
            'sale_with_negative_stock' => 'required',
            'price'                    => 'required|numeric',
        ],
    ];

    public function __construct(Validator $validator, StoreProductRepository $repository, StoreProductHasStoreProductAttributeService $storeProductsHasStoreProductAttributesServices)
    {
        $this->validator  = $validator;
        $this->repository = $repository;

        $this->storeProductsHasStoreProductAttributesServices = $storeProductsHasStoreProductAttributesServices;
    }

    /**
     * map data to main product
     * @param array $data
     * @return array
     */
    private function mapDataStoreProduct(array $data)
    {
        return [
            'id'                        => isset($data['id']) ? (int) $data['id'] : null,
            'name'                      => $data['name'],
            'store_product_category_id' => $data['store_product_category_id'],
            'store_product_brand_id'    => $data['store_product_brand_id'],
            'status'                    => isset($data['status']) ? $data['status'] : 0,
            'sale_with_negative_stock'  => isset($data['sale_with_negative_stock']) ? $data['sale_with_negative_stock'] : 0,
            'price'                     => FloatConversor::convert($data['price']),
            'cost_price'                => FloatConversor::convert($data['cost_price']),
            'sku'                       => $data['sku'],
            'weight'                    => (float) $data['weight'],
            'total_in_stock'            => !empty($data['total_in_stock']) ? $data['total_in_stock'] : 0,
            'description'               => $data['description'],
        ];
    }

    /**
     * map data to subproduct
     * @param StoreProduct $mainProduct
     * @param array data
     * @return array
     */
    private function mapDataStoreSubproduct(StoreProduct $mainProduct, array $data)
    {
        $dataMap     = $this->mapDataStoreProduct($mainProduct->getAttributes());
        $dataReplace = [
            'store_product_id' => $mainProduct->id,
            'id'               => isset($data['id']) ? (int) $data['id'] : null,
            'sku'              => $data['sku'],
            'weight'           => (float) $data['weight'],
            'total_in_stock'   => !empty($data['total_in_stock']) ? $data['total_in_stock'] : 0,
            'price'            => FloatConversor::convert($data['price']),
            'cost_price'       => FloatConversor::convert($data['cost_price']),
        ];
        return array_replace($dataMap, $dataReplace);
    }

    /**
     * persist subproduct with yours attributes
     * @param StoreProduct $mainProduct
     * @param array $dataSubproduct
     * @param array $attributes
     * @return StoreProduct
     */
    private function storeSubproduct(StoreProduct $mainProduct, array $dataSubproduct, array $attributes)
    {
        $mapData = $this->mapDataStoreSubproduct($mainProduct, $dataSubproduct);
        if (!empty($mapData['id'])) {
            $this->storeProductsHasStoreProductAttributesServices->deleteBy('store_product_id', $mapData['id']);
        }
        $modelSubproduct = parent::store($mapData, 'create');

        // store attributes
        if (isset($dataSubproduct['product_attribute'])) {
            foreach ($dataSubproduct['product_attribute'] as $i => $value) {
                $dataAttribute = [
                    'store_product_id'           => $modelSubproduct->id,
                    'store_product_attribute_id' => $attributes[$i],
                    'value'                      => $value,
                ];
                $this->storeProductsHasStoreProductAttributesServices->store($dataAttribute, 'create');
            }
        }
        return $modelSubproduct;
    }

    /**
     * persist product
     * verify exists subproducts and attributes for persist
     * @param array $data
     * @param String $rules
     * @return array
     */
    public function store(array $data, $rules = false)
    {
        $idsChecked  = [];
        $productData = $this->mapDataStoreProduct($data['StoreProduct']);
        $mainProduct = parent::store($productData, $rules);
        if (isset($data['subproduct-checked']) && null !== $data['subproduct-checked']) {
            foreach ($data['subproduct-checked'] as $key => $subproduct) {
                $idsChecked[] = $subproduct['checkbox'];
                $this->storeSubproduct($mainProduct, $data['subproduct'][$key], $data['StoreProductAttributes']);
            }
        }
        // remove product unchecked
        $unCheckeds = $this->repository
            ->select('id')
            ->where('store_product_id', '=', $mainProduct->id)
            ->whereNotIn('id', $idsChecked)
            ->get()
            ->toArray();
        if ($unCheckeds) {
            $this->repository->destroy($unCheckeds);
        }
        return $mainProduct;
    }

    public function getAll($search = null)
    {
        return $this->repository->getAll($search);
    }

}
