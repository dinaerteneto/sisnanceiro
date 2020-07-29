<?php

namespace Sisnanceiro\Transformers;

use Carbon\Carbon;
use League\Fractal\TransformerAbstract;
use Sisnanceiro\Helpers\Mask;

class CashFlowDetailTransformer extends TransformerAbstract {
	public function transform($data) {
		$date = Carbon::createFromFormat('Y-m-d', $data->date);
		$ret = [];
		if ($data) {
			$ret = [
				'username' => $data->user_name,
				'note' => nl2br($data->note),
				'category' => $data->category,
				'parcel_number' => $data->parcel_number,
				'total_parcels' => $data->total_parcels,
				'net_value' => Mask::currency($data->net_value),
				'gross_value' => Mask::currency($data->gross_value),
				'tax_value' => Mask::currency($data->tax_value),
				'date' => $date->format('d/m/Y'),
				'bank_account_name' => $data->bank_account_name,

				'date_original' => $data->date,
				'net_value_original' => $data->net_value,
				'gross_value_original' => $data->gross_value,
			];
		}
		return $ret;
	}

}
