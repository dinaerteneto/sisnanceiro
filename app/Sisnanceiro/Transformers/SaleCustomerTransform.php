<?php
namespace Sisnanceiro\Transformers;

use League\Fractal\TransformerAbstract;

class SaleCustomerTransform extends TransformerAbstract
{
    public function transform($data)
    {
        return [
            'id'        => (int) $data->id,
            'data'      => $data,
            'selection' => "{$data->firstname} {$data->lastname}",
        ];

    }

}
