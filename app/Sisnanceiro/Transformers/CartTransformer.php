<?php
namespace Sisnanceiro\Transformers;

use Carbon\Carbon;
use League\Fractal\TransformerAbstract;
use Sisnanceiro\Helpers\Mask;
use Sisnanceiro\Models\Person;
use Sisnanceiro\Models\User;
use Sisnanceiro\Models\StoreProduct;

class CartTransformer extends TransformerAbstract
{

    public function transform($cart)
    {
        $ret = [];
        if ($cart) {
            $saleCarbonDate = Carbon::createFromFormat('Y-m-d H:i:s', $cart->created_at);
            $transformItems = $this->transformItems($cart->items);
            $ret = [
                'id' => $cart->id,
                'token' => $cart->token,
                'sale_code' => $cart->company_sale_code,
                'gross_value' => Mask::currency($cart->gross_value),
                'gross_value_no_mask' => $cart->gross_value,
                'sale_date' => $saleCarbonDate->format('d/m/Y'),

                'discount_value' => Mask::currency($cart->discount_value),
                'discount_value_no_mask' => $cart->discount_value,
                'discount_type' => $cart->discount_type,

                'net_value' => Mask::currency($cart->net_value),
                'net_value_no_mask' => $cart->net_value,

                'companyName' => strtoupper($cart->company_name),

                'company' => $this->transformPerson($cart->company_id),
                'customer' => $this->transformPerson($cart->customer_id),
                'userCreated' => $this->transformPerson($cart->user_id_created),

                'items' => $transformItems['items'],
                'itemQuantity' => $transformItems['total_quantity'],
            ];
        }
        return $ret;
    }

    private function transformUser(User $user)
    {
        $person = $user->person;
        return [
            'name' => "{$person->firstname} {$person->lastname}",
        ];
    }

    private function transformPerson($personId)
    {
        if ($personId) {
            $address = null;
            $contacts = null;
            $person = Person::find($personId);
            if ($person) {
                $address = $person->addresses->first();
                $contacts = $person->contacts;

                $email = null;
                $phone = null;
                /*
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
                 */

                return [
                    'id' => $person->id,
                    'name' => "{$person->firstname} {$person->lastname}",
                    'cpf-cnpj' => $person->cpf,
                    'address' => [
                        'zip_code' => !empty($address) ? $address->zip_code : null,
                        'address' => !empty($address) ? $address->address : null,
                        'number' => !empty($address) ? $address->number : null,
                        'complement' => !empty($address) ? $address->complement : null,
                        'reference' => !empty($address) ? $address->reference : null,
                        'city' => !empty($address) ? $address->city : null,
                        'district' => !empty($address) ? $address->district : null,
                        'uf' => !empty($address) ? $address->uf : null,
                    ],
                    'contact' => [
                        'email' => $email,
                        'phone' => $phone,
                    ],
                ];
            }
        }
        return ['name' => 'Ao Consumidor'];
    }

    private function transformItems($items)
    {
        $quantity = 0;
        $return = ['items' => [], 'total_quantity' => 0];
        foreach ($items as $item) {
            $companyName = 'teste';
            $discountValue = 0;
            $discountValueNoMask = $item->discount_value;
            if (!empty($discountValue) && $discountValue > 0) {
                $discountValue = Mask::currency($item->discount_value) . ' ' . $item->discount_value_type;
            }

            $quantity += $item->quantity;
            $return['items'][] = [
                'id' => $item->sale_item_id,
                'store_product_id' => $item->store_product_id,

                'quantity' => Mask::float($item->quantity),
                'quantity_no_mask' => $item->quantity,

                'unit_value' => Mask::currency($item->unit_value),
                'unit_value_no_mask' => $item->unit_value,

                'discount_value' => $discountValue,
                'discount_value_no_mask' => $discountValueNoMask,
                'discount_type' => $item->discount_type,

                'unit_measurement' => $item->unit_measurement,

                'total_value' => Mask::currency($item->total_value),
                'total_value_no_mask' => $item->total_value,

                'company_name' => $companyName,
                'product' => $this->transformProduct($item),
            ];
        }
        $return['total_quantity'] = Mask::currency($quantity);
        return $return;
    }

    private function transformProduct($product)
    {
        if ($product) {
            $product = StoreProduct::find($product->store_product_id);
            return [
                'sku' => $product->sku,
                'name' => $product->name,
                'unit_measurement' => $product->unit_measurement,
            ];
        }
        return ['name' => ''];
    }

}
