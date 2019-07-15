<?php

namespace Sisnanceiro\Transformers;

use League\Fractal\TransformerAbstract;
use Sisnanceiro\Helpers\Mask;

class SaleStoreProductTransformer extends TransformerAbstract
{
    public function transform($data)
    {
        $attrs = explode(',', $data->attributes);

        $html = "
            <tr class=\"clearfix\" style=\"opacity: 1;\">
                <td width=\"8%\" style=\"padding-right: 10px;\">  </td>
                <td valign=\"middle\" width=\"46%\">
                    <span><strong>{$data->name}</strong></span> <br />
                    <span>SKU: {$data->sku}</span> <br />
                </td>
                <td valign=\"middle\" width=\"26%\">
        ";
        if ($attrs) {
            foreach ($attrs as $attr) {
                if ($attr) {
                    $html .= "<span class=\"label label-inverse\" style=\"min-width: 80px\">{$attr}</span>&nbsp;";
                }
            }
        }

        $html .= "</td>
                <td valign=\"middle\" align=\"center\" width=\"20%\">
                    <b>Valor:</b> R$ {$data->price}
                </td>
            </tr>";

        $data->price = Mask::float($data->price);

        return [
            'id'        => (int) $data->id,
            'product'   => $data,
            'html'      => $html,
            'selection' => "{$data->name} {$data->attributes}",
        ];

    }

}
