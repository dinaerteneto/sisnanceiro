<?php
namespace Sisnanceiro\Transformers;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use League\Fractal\TransformerAbstract;
use Sisnanceiro\Helpers\Mask;
use Sisnanceiro\Models\Person;
use Sisnanceiro\Models\PersonContactType;
use Sisnanceiro\Models\Sale;
use Sisnanceiro\Models\User;

class SaleTransformer extends TransformerAbstract
{

    public function transform(Sale $sale)
    {
        $saleCarbonDate = Carbon::createFromFormat('Y-m-d H:i:s', $sale->created_at);
        $transformItems = $this->transformItems($sale->items);
        return [
            'id'                     => $sale->id,
            'sale_code'              => $sale->company_sale_code,
            'gross_value'            => Mask::currency($sale->gross_value),
            'gross_value_no_mask'    => $sale->gross_value,

            'discount_value'         => Mask::currency($sale->discount_value),
            'discount_value_no_mask' => $sale->discount_value,

            'net_value'              => Mask::currency($sale->net_value),
            'net_value_no_mask'      => $sale->net_value,

            'sale_date'              => $saleCarbonDate->format('d/m/Y'),
            'sale_hour'              => $saleCarbonDate->format('H:i'),
            'status'                 => Sale::getStatus($sale->status),
            'companyName'            => strtoupper($sale->company->person->firstname),
            'company'                => $this->transformPerson($sale->company->person),
            'userCreated'            => $this->transformUser($sale->userCreated),
            'customer'               => isset($sale->customer) ? $this->transformPerson($sale->customer->person) : null,
            'items'                  => $transformItems['items'],
            'itemQuantity'           => $transformItems['total_quantity'],
        ];
    }

    private function transformUser(User $user)
    {
        $person = $user->person;
        return [
            'name' => "{$person->firstname} {$person->lastname}",
        ];
    }

    private function transformPerson(Person $person = null)
    {
        if ($person) {
            $address  = $person->addresses->first();
            $contacts = $person->contacts;
            $email    = null;
            $phone    = null;
            if ($contacts) {
                foreach ($contacts as $contact) {
                    if ($contact->person_contact_type_id == PersonContactType::TYPE_CELLPHONE) {
                        $phone = $contact->value;
                    }
                    if ($contact->person_contact_type_id == PersonContactType::TYPE_EMAIL) {
                        $email = $contact->value;
                    }
                }
            }

            return [
                'id'       => $person->id,
                'name'     => "{$person->firstname} {$person->lastname}",
                'cpf-cnpj' => $person->cpf,
                'address'  => [
                    'zip_code'   => !empty($address) ? $address->zip_code : null,
                    'address'    => !empty($address) ? $address->address : null,
                    'number'     => !empty($address) ? $address->number : null,
                    'complement' => !empty($address) ? $address->complement : null,
                    'reference'  => !empty($address) ? $address->reference : null,
                    'city'       => !empty($address) ? $address->city : null,
                    'district'   => !empty($address) ? $address->district : null,
                    'uf'         => !empty($address) ? $address->uf : null,
                ],
                'contact'  => [
                    'email' => $email,
                    'phone' => $phone,
                ],
            ];
        }
        return ['name' => 'Ao Consumidor'];
    }

    private function transformItems(Collection $items)
    {
        $quantity = 0;
        $return   = ['items' => [], 'total_quantity' => 0];
        foreach ($items as $item) {
            $companyName         = 'teste';
            $discountValue       = 0;
            $discountValueNoMask = $item->discount_value;
            if (!empty($discountValue) && $discountValue > 0) {
                $discountValue = Mask::currency($item->discount_value) . ' ' . $item->discount_value_type;
            }

            $quantity += $item->quantity;
            $return['items'][] = [
                'id'                     => $item->id,
                'store_product_id'       => $item->store_product_id,

                'quantity'               => Mask::float($item->quantity),
                'quantity_no_mask'       => $item->quantity,

                'unit_value'             => Mask::currency($item->unit_value),
                'unit_value_no_mask'     => $item->unit_value,

                'discount_value'         => $discountValue,
                'discount_value_no_mask' => $discountValueNoMask,
                'discount_type'          => $item->discount_value_type,

                'total_value'            => Mask::currency($item->total_value),
                'total_value_no_mask'    => $item->total_value,

                'company_name'           => $companyName,
                'product'                => $this->transformProduct($item),
            ];
        }
        $return['total_quantity'] = Mask::currency($quantity);
        return $return;
    }

    private function transformProduct($product)
    {
        if ($product) {
            $product = $product->product()->first();
            return [
                'sku'              => $product->sku,
                'name'             => $product->name,
                'unit_measurement' => $product->unit_measurement,
            ];
        }
        return ['name' => ''];
    }

}
