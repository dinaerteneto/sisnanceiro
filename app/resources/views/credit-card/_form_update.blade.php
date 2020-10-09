<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <form id="bank-credit-card-form" class="bank-transaction-form" method="post" action="{{ url($action) }}">
            @csrf
            <div class="modal-header">
                <h4 class="modal-title">{{ $title }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">×</span>
                </button>

            </div>
            <div class="modal-body">
                <fieldset>

                    <div class="row mb-10">
                        <div class="col-sm-8">
                            <label class="control-label" for="CreditCard_name">Nome</label>
                            <input type="text" name="CreditCard[name]" id="CreditCard_name" class="form-control" value="{{$model->name}}" />
                        </div>
                        <div class="col-sm-4">
                            <label class="control-label" for="CreditCard_limit">Limite</label>
                            <input type="text" name="CreditCard[limit]" id="CreditCard_limit" class="form-control mask-currency" value="{{ $model->limit }}" />
                        </div>
                    </div>

                    <div class="row mb-10">
                        <div class="col-sm-4">
                            <label class="control-label" for="CreditCard_credit_card_brand_id">Bandeira</label>
                            <select name="CreditCard[credit_card_brand_id]" id="CreditCard_credit_card_brand_id" class="select2">
                                @if($creditCardBrands)
                                    @foreach($creditCardBrands as $creditCardBrand)
                                        <option value="{{ $creditCardBrand->id }}" {{ $model->credit_card_brand_id == $creditCardBrand->id ? 'selected' : null }}>{{ $creditCardBrand->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <div class="col-sm-4">
                            <label class="control-label" for="CreditCard_bank_account_id">Conta</label>
                            <select name="CreditCard[bank_account_id]" id="CreditCard_bank_account_id" class="select2">
                                @if($bankAccounts)
                                    @foreach($bankAccounts as $bankAccount)
                                        <option value="{{ $bankAccount->id }}" {{ $model->bank_account_id == $bankAccount->id ? 'selected' : null }}>{{ $bankAccount->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>

                    <div class="row mb-10">

                        <div class="col-sm-4">
                            <label class="control-label" for="CreditCard_closing_day">Dia de fechamento</label>
                            <select name="CreditCard[closing_day]" id="CreditCard_closing_day" class="select2">
                                @for ($i = 1; $i <= 30; $i++)
                                    <option value="{{ $i }}" {{ $model->closing_day == $i ? 'selected' : null }}>{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <label class="control-label" for="CreditCard_payment_day">Dia de pagamento</label>
                            <select name="CreditCard[payment_day]" id="CreditCard_payment_day" class="select2">
                                @for ($i = 1; $i <= 30; $i++)
                                    <option value="{{ $i }}" {{ $model->payment_day == $i ? 'selected' : null }}>{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>

                </fieldset>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Enviar</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            </div>

        </form>
    </div>
</div>

@section('scripts')

@include('layouts/_partial_scripts')
<script type="text/javascript" src="{{ asset('assets/js/custom/BankTransaction.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/custom/CreditCard.js') }}"></script>
<script type="text/javascript">
$('document').ready(function(){

    $('#a-more-info').on('click', function(e) {
        e.preventDefault();
        if ($('div#more-info').hasClass('d-none')) {
            $('div#more-info').removeClass('d-none');
            $('#a-more-info').html('Menos informações');

            $('#BankInvoiceTransaction_total_invoice').val(1);
            $('#BankInvoiceTransaction_total_invoice').prop('disabled', true);
            $('#BankInvoiceTransaction_type_cycle').prop('disabled', true);

            $('#BankInvoiceTransaction_repeat').prop('checked', false);
            $('#BankInvoiceTransaction_fixed').prop('checked', false);
        } else {
            $('div#more-info').addClass('d-none');
            $('#a-more-info').html('Mais informações');
        }
    });

    $('#BankInvoiceTransaction_fixed').on('click', function() {
        if($(this).is(':checked')) {
            $('#BankInvoiceTransaction_repeat').prop('checked', false);
            $('#BankInvoiceTransaction_total_invoice').val(1);
            $('#BankInvoiceTransaction_total_invoice').prop('disabled', true);
            $('#BankInvoiceTransaction_type_cycle').prop('disabled', true);
        }
    });

    $('#BankInvoiceTransaction_repeat').on('click', function() {
        if($(this).is(':checked')) {
            $('#BankInvoiceTransaction_fixed').prop('checked', false);
            $('#BankInvoiceTransaction_total_invoice').val(1);
            $('#BankInvoiceTransaction_total_invoice').prop('disabled', false);
            $('#BankInvoiceTransaction_type_cycle').prop('disabled', false);
        } else {
            $('#BankInvoiceTransaction_total_invoice').prop('disabled', true);
            $('#BankInvoiceTransaction_type_cycle').prop('disabled', true);
        }
    });

    $('#BankInvoiceDetail_status').on('click', function() {
        $('#BankInvoiceDetail_payment_date').val('');
        if($(this).is(':checked')) {
            $('#BankInvoiceDetail_payment_date').prop('disabled', false);
        }
        else {
            $('#BankInvoiceDetail_payment_date').prop('disabled', true);
        }
    });
});
</script>
