<?php

namespace Sisnanceiro\Transformers;

use Illuminate\Support\Collection;
use League\Fractal\TransformerAbstract;
use Sisnanceiro\Helpers\Mask;
use Sisnanceiro\Models\StoreProduct;
use Sisnanceiro\Models\StoreProductBrand;
use Sisnanceiro\Models\StoreProductCategory;

class StoreProductTransformer extends TransformerAbstract
{

    /**
     * Transform the product
     * @param StoreProduct $storeProduct
     * @return array
     */
    public function transform(StoreProduct $storeProduct)
    {
        return [
            'id'                       => $storeProduct->id,
            'name'                     => $storeProduct->name,
            'sku'                      => $storeProduct->sku,
            'price'                    => Mask::currency($storeProduct->price),
            'weight'                   => $storeProduct->weight,
            'description'              => $storeProduct->description,
            'sale_with_negative_stock' => $storeProduct->sale_with_negative_stock,
            'status'                   => $storeProduct->status,
            'total_in_stock'           => $storeProduct->total_in_stock,
            'category'                 => $this->transformCategory($storeProduct->category()->get()->first()),
            'brand'                    => $this->transformBrand($storeProduct->brand()->get()->first()),
            'subproducts'              => $this->transformSubproducts($storeProduct->subproducts()->get()),
        ];
    }

    private function transformCategory(StoreProductCategory $storeProductCategory = null)
    {
        if ($storeProductCategory) {
            return [
                'id'   => $storeProductCategory->id,
                'name' => $storeProductCategory->name,
            ];
        }
        return [];
    }

    private function transformBrand(StoreProductBrand $storeProductBrand = null)
    {
        if ($storeProductBrand) {
            return [
                'id'   => $storeProductBrand->id,
                'name' => $storeProductBrand->name,
            ];
        }
        return [];
    }

    private function transformSubproducts(Collection $products)
    {
        $subproducts = [];
        foreach ($products as $product) {
            $subproducts[] = [
                'id'                       => $product->id,
                'name'                     => $product->name,
                'sku'                      => $product->sku,
                'price'                    => Mask::currency($product->price),
                'weight'                   => $product->weight,
                'description'              => $product->description,
                'sale_with_negative_stock' => $product->sale_with_negative_stock,
                'status'                   => $product->status,
                'total_in_stock'           => $product->total_in_stock,
                'attributes'               => $this->transformAttributes($product->attributes()->get()),
            ];
        }
        if ($subproducts) {
            $subproducts['variables'] = $subproducts[0]['attributes'];
            foreach ($subproducts['variables'] as $key => $variable) {
                $recordValues = \DB::table('store_product_has_store_product_attribute')
                    ->select('store_product_attribute_id', 'value')
                    ->whereIn('store_product_id', \DB::table('store_product')
                            ->select('id')
                            ->where('store_product_id', $product->store_product_id))
                    ->where('store_product_attribute_id', $variable['store_product_attribute_id'])
                    ->groupBy('store_product_attribute_id', 'value')
                    ->get();
                foreach ($recordValues as $recordValue) {
                    $subproducts['variables'][$key]['values'][] = $recordValue->value;
                }
            }

            foreach ($subproducts as $key => $subproduct) {
                if (isset($subproduct['attributes']) && count($subproduct['attributes']) > 0) {
                    foreach ($subproduct['attributes'] as $attr) {
                        $subproducts[$key]['id_attribute'][] = $attr['value'];
                    }
                    $subproducts[$key]['id_attribute'] = implode('-', $subproducts[$key]['id_attribute']);
                }
            }
        }
        return $subproducts;
    }

    private function transformAttributes(Collection $attributes)
    {
        $return = [];
        foreach ($attributes as $attribute) {
            $oAttr    = $attribute->productAttributes->first();
            $return[] = [
                'id'                         => $oAttr->id,
                'store_product_attribute_id' => $attribute['store_product_attribute_id'],
                'store_product_id'           => $attribute['store_product_id'],
                'name'                       => $oAttr->name,
                'value'                      => $attribute['value'],
            ];
        }
        return $return;
    }
}
