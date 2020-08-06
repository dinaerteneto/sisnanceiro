 <?php
$term = null;
if (!empty($model->id)) {
    $term = $model->paymentTaxTerm[0];
}
?>
<div class="row mb-10">
    <div class="col-sm-4">
        <label class="control-label">Tipo de valor</label>
        <select name="PaymentTaxTerm[percent]" class="form-control">
            <option value="0" {{ !empty($term) && $term->percent == 0 ? 'selected' : null }} >Valor R$</option>
            <option value="1" {{ !empty($term) && $term->percent == 1 ? 'selected' : null }}>Porcentagem</option>
        </select>
    </div>
    <div class="col-sm-4">
        <label class="control-label">Valor</label>
        <input type="text" name="PaymentTaxTerm[value]" class="form-control mask-float" placeholder="Valor da taxa" value="{{ !empty($term) ? $term->value : '0,00' }}" autocomplete="off">
    </div>
    <input type="hidden" name="PaymentTaxTerm[id]" value="{{ !empty($term) ? $term->id : null }}">
</div>

