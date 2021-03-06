<h5>Parcelamento</h5>
<div id="credit-card-body" class="">
    @if ($model->id)
        @foreach ($model->paymentTaxTerm as $paymentTaxTerm)
        <div class="clearfix credit-card-field row mb-10">
            <div class="col-sm-4">
                <input type="text" name="CreditCard[order][]" class="form-control mask-number credit-card-order" placeholder="N. de vezes" value="{{ $paymentTaxTerm->order }}" />
            </div>
            <div class="col-sm-4">
                <input type="text" name="CreditCard[value][]" class="form-control mask-float credit-card-value" placeholder="%" value="{{ $paymentTaxTerm->value }}" />%
            </div>
            <div class="col-sm-4">
                <button class="btn btn-sm btn-default del-credit-card-tax"><i class="fa fa-trash-o"></i></button>
            </div>
        </div>
        @endforeach
    @else
    <div class="clearfix credit-card-field row mb-10">
        <div class="col-sm-4">
            <input type="text" name="CreditCard[order][]" class="form-control mask-number credit-card-order" placeholder="N. de vezes" value="" />
        </div>
        <div class="col-sm-4">
            <input type="text" name="CreditCard[value][]" class="form-control mask-float credit-card-value" placeholder="%" value="" />%
        </div>
        <div class="col-sm-4">
            <button class="btn btn-sm btn-default del-credit-card-tax"><i class="fa fa-trash-o"></i></button>
        </div>
    </div>
    @endif

</div>

<div class="clearfix form-group">
    <button type="button" class="btn btn-sm" id="add-credit-card-tax"><i class="fa fa-plus"></i> Add </button>
</div>
