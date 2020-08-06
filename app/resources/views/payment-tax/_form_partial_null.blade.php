<?php
$term = null;
if (!empty($model->id)) {
    $term = $model->paymentTaxTerm[0];
}
?>
<input type="hidden" name="PaymentTaxTerm[percent]" value="{{ $term ? $term->percent : 0 }}" />
<input type="hidden" name="PaymentTaxTerm[value]" value="{{ $term ? $term->value : 0}}" />
<input type="hidden" name="PaymentTaxTerm[id]" value="{{ $term ? $term->id : null }}" />
