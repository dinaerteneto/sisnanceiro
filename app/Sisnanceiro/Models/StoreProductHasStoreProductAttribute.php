<?php

namespace Sisnanceiro\Models;
use Illuminate\Database\Eloquent\Model;
use App\Scopes\TenantModels;

class StoreProductHasStoreProductAttribute extends Model
{
    use TenantModels;

    protected $table      = 'store_product_has_store_product_attribute';
    // protected $primaryKey = ['store_product_id', 'store_product_attribute_id'];

    protected $fillable = [
        'store_product_id',
        'store_product_attribute_id',
        'value',
    ];

    protected function setKeysForSaveQuery(\Illuminate\Database\Eloquent\Builder $query)
    {
        $query
            ->where('store_product_id', '=', $this->getAttribute('store_product_id'))
            ->where('store_product_attribute_id', '=', $this->getAttribute('store_product_attribute_id'));
        return $query;
    }

    public function productAttributes()
    {
        return $this->hasMany('Sisnanceiro\Models\StoreProductAttribute', 'id', 'store_product_attribute_id');
    }
}
