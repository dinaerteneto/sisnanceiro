<?php

namespace Sisnanceiro\Transformers;

use League\Fractal\TransformerAbstract;
use Sisnanceiro\Helpers\Mask;

class BankTransactionTotalTransformer extends TransformerAbstract
{

    public function transform($data)
    {
        return [
            'to_receive' => $data->to_receive,
            'to_pay'     => $data->to_pay,
            'total'      => $data->total,
            'mask'       => [
                'to_receive' => Mask::currency($data->to_receive),
                'to_pay'     => Mask::currency($data->to_pay),
                'total'      => Mask::currency($data->total),
                'str'        => ($data->str),
            ],
        ];
    }

}
