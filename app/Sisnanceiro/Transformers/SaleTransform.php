<?php
namespace Sisnanceiro\Transformers;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use League\Fractal\TransformerAbstract;
use Sisnanceiro\Helpers\Mask;
use Sisnanceiro\Models\Customer;
use Sisnanceiro\Models\Sale;
use Sisnanceiro\Models\StoreProduct;
use Sisnanceiro\Models\User;

class SaleTransform extends TransformerAbstract
{

    public function transform(Sale $sale)
    {
        $saleCarbonDate = Carbon::createFromFormat('Y-m-d H:i:s', $sale->created_at);
        return [
            'id'          => $sale->id,
            'sale_code'   => $sale->company_sale_code,
            'net_value'   => Mask::currency($sale->net_value),
            'sale_date'   => $saleCarbonDate->format('d/m/Y'),
            'sale_hour'   => $saleCarbonDate->format('H:i'),
            'status'      => Sale::getStatus($sale->status),
            'companyName' => strtoupper($sale->company->person->firstname),
            'userCreated' => $this->transformUser($sale->userCreated),
            'customer'    => $this->transformCustomer($sale->customer),
            'items'       => $this->transformItems($sale->items),
        ];
    }

    private function transformUser(User $user)
    {
        $person = $user->person;
        return [
            'name' => "{$person->firstname} {$person->lastname}",
        ];
    }

    private function transformCustomer(Customer $customer = null)
    {
        if ($customer) {
            $person = $customer->person;
            return ['name' => "{$person->firstname} {$person->lastname}"];
        }
        return ['name' => 'Ao Consumidor'];
    }

    private function transformItems(Collection $items)
    {
        $return = [];
        foreach ($items as $item) {
            $companyName   = 'teste';
            $discountValue = 0;
            if (!empty($discountValue) && $discountValue > 0) {
                $discountValue = Mask::currency($item->discount_value) . ' ' . $item->discount_value_type;
            }

            $return[] = [
                'id'               => $item->id,
                'store_product_id' => $item->store_product_id,
                'quantity'         => Mask::float($item->quantity),
                'unit_value'       => Mask::currency($item->unit_value),
                'discount_value'   => $discountValue,
                'total_value'      => Mask::currency($item->total_value),
                'company_name'     => $companyName,
                'product'          => $this->transformProduct($item->product),
            ];
        }
        return $return;
    }

    private function transformProduct(StoreProduct $product)
    {
        return [
            'name' => $product->name,
        ];
    }

}
