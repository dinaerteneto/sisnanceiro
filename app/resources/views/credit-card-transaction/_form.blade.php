<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <form id="bank-transaction-form" class="bank-transaction-form" method="post" action="{{ $action }}" onsubmit="return false">
            @csrf

            <input type="hidden" value="{{ Sisnanceiro\Models\BankCategory::CATEGORY_TO_PAY }}" id="main_category_id" />

            <div class="modal-header">
                <h4 class="modal-title">{{ $title }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">×</span>
                </button>

            </div>
            <div class="modal-body">
                <fieldset>
                    <div class="row mb-10">
                        <div class="col-sm-4">
                            <label class="control-label" for="BankInvoiceDetail_net_value">Insira o valor</label>
                            <input type="text" name="BankInvoiceDetail[net_value]" id="BankInvoiceDetail_net_value" class="form-control mask-currency" value="{{ $model->net_value }}" />
                        </div>
                        <div class="col-sm-4">
                            <label class="control-label" for="BankInvoiceDetail_competence_date">Data da compra</label>
                            <input type="text" name="BankInvoiceDetail[competence_date]" id="BankInvoiceDetail_competence_date" class="form-control datepicker" value="{{ $model->competence_date }}" />
                        </div>
                      <div class="col-sm-4">
                            <label class="control-label" for="BankInvoiceDetail_due_date">Para vencto em</label>
                            <select name="BankInvoiceDetail[due_date]" id="BankInvoiceDetail_due_date" class="select2"></select>
                        </div>
                    </div>

                    <div class="row mb-10">
                        <div class="col-sm-12">
                            <label class="control-label" for="BankInvoiceTransaction_description">Descrição</label>
                            <textarea name="BankInvoiceTransaction[description]" id="BankInvoiceTransaction_description" class="form-control">{{$model->description}}</textarea>
                        </div>
                    </div>

                    <div class="row mb-10">
                        <div class="col-sm-6">
                            <label class="control-label" for="BankInvoiceDetail_bank_category_id">Categoria</label>
                            <input name="BankInvoiceDetail[bank_category_id]" id="BankInvoiceDetail_bank_category_id" class="control-form">
                        </div>
                        <div class="col-sm-6">
                            <label class="control-label" for="BankInvoiceDetail_credit_card_id">Cartão de crédito</label>
                            <select name="BankInvoiceDetail[credit_card_id]" id="BankInvoiceDetail_credit_card_id" class="select2">
                                @if($creditCards)
                                    @foreach($creditCards as $creditCard)
                                        <option value="{{ $creditCard->id }}" {{ $model->credit_card_id == $creditCard->id ? 'selected' : null }}>{{ $creditCard->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>

                    <div class="row mb-10">
                        <div class="col-sm-12">
                            <label class="control-label" for="BankInvoiceTransaction_note">Observação</label>
                            <textarea name="BankInvoiceTransaction[note]" id="BankInvoiceTransaction_note" class="form-control">{{$model->note}}</textarea>
                        </div>
                    </div>

                    <div class="row mb-10">
                        <div class="col-sm-12 text-center font-italic">
                            <a href="javascript:void(0)" id="a-more-info">Mais informações</a>
                        </div>
                    </div>

                    <div id="more-info" class="d-none">

                        <div class="row mb-10">
                            <div class="col-sm-4" style="margin-top: 8px">
                                <label class="vcheck m-0">
                                    <input type="checkbox" class="checkbox style-0" name="BankInvoiceTransaction[repeat]" id="BankInvoiceTransaction_repeat" value="1" {{ !empty($model->repeat) ? 'checked' : null }} >
                                    <span>Parcelado</span>
                                </label>
                            </div>
                            <div class="col-sm-4">
                                <input type="text" name="BankInvoiceTransaction[total_invoice]" id="BankInvoiceTransaction_total_invoice" class="mask-number form-control" value="1" disabled />
                            </div>
                            <input type="hidden" name="BankInvoiceTransaction[type_cycle]" value="{{ \Sisnanceiro\Models\BankInvoiceTransaction::TYPE_CYCLE_MONTHLY }}" />
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
<script type="text/javascript">
var data = {!! $categoryOptions !!}
</script>

@include('layouts/_partial_scripts')
<script type="text/javascript" src="{{ asset('assets/js/custom/BankTransaction.js') }}"></script>
<script type="text/javascript">
$('document').ready(function(){

    venctos(<?=$id;?>);

    $('#a-more-info').on('click', function(e) {
        e.preventDefault();
        if ($('div#more-info').hasClass('d-none')) {
            $('div#more-info').removeClass('d-none');
            $('#a-more-info').html('Menos informações');

            $('#BankInvoiceTransaction_total_invoice').val(1);
            $('#BankInvoiceTransaction_total_invoice').prop('disabled', true);
            $('#BankInvoiceTransaction_type_cycle').prop('disabled', true);

            $('#BankInvoiceTransaction_repeat').prop('checked', false);
        } else {
            $('div#more-info').addClass('d-none');
            $('#a-more-info').html('Mais informações');
        }
    });

    $('#BankInvoiceTransaction_repeat').on('click', function() {
        if($(this).is(':checked')) {
            $('#BankInvoiceTransaction_total_invoice').val(1);
            $('#BankInvoiceTransaction_total_invoice').prop('disabled', false);
            $('#BankInvoiceTransaction_type_cycle').prop('disabled', false);
        } else {
            $('#BankInvoiceTransaction_total_invoice').prop('disabled', true);
            $('#BankInvoiceTransaction_type_cycle').prop('disabled', true);
        }
    });

    function venctos(creditCardId) {
        $.ajax({
            type: 'POST',
            url: `/credit-card/${creditCardId}/due-invoice-dates`,
            dataType: 'json',
            success: function(json) {
                let html = '';
                let value = '';
                $.each(json.dates, function(el, item) {
                    const selected = '';
                    if(item.selected) {
                        value = item.date;
                        const selected = 'selected';
                    }
                    html += `<option value="${item.date}" ${selected}>${item.date}</option> \n`;
                });
                $('#BankInvoiceDetail_due_date').html(html);
                $('#BankInvoiceDetail_due_date').val(value);
                $('#BankInvoiceDetail_due_date').trigger('change');
            }
        })
    }

});
</script>
