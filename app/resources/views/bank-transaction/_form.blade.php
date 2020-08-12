<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <form id="bank-transaction-form" class="bank-transaction-form" method="post" action="{{ $action }}" onsubmit="return false">
            @csrf

            <input type="hidden" value="{{ $mainCategory['main_category_id'] }}" id="main_category_id" />

            <div class="modal-header">
                <h4 class="modal-title">{{ $title }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">×</span>
                </button>

            </div>
            <div class="modal-body">
                <fieldset>
                    <div class="row mb-10">
                        <div class="col-sm-3">
                            <label class="control-label" for="BankInvoiceDetail_net_value">Insira o valor</label>
                            <input type="text" name="BankInvoiceDetail[net_value]" id="BankInvoiceDetail_net_value" class="form-control mask-currency" value="{{ $model->net_value }}" />
                        </div>
                        <div class="col-sm-3">
                            <label class="control-label" for="BankInvoiceDetail_due_date">Data de vencto</label>
                            <input type="text" name="BankInvoiceDetail[due_date]" id="BankInvoiceDetail_due_date" class="form-control datepicker" value="{{ $model->due_date }}" />
                        </div>

                        @if($mainCategory['main_category_id'] == Sisnanceiro\Models\BankCategory::CATEGORY_TO_PAY)
                        <div class="col-sm-6">
                            <label class="control-label" for="BankInvoiceDetail_supplier_id">Fornecedor</label>
                            <select name="BankInvoiceDetail[supplier_id]" id="BankInvoiceDetail_supplier_id" class="select2">
                                @if($suppliers)
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}" {{ $model->supplier_id == $supplier->id ? 'selected' : null }}>{{ $supplier->firstname }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        @else
                        <div class="col-sm-6">
                            <label class="control-label" for="BankInvoiceDetail_customer_id">Cliente</label>
                            <select name="BankInvoiceDetail[customer_id]" id="BankInvoiceDetail_customer_id" class="select2">
                                @if($customers)
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}" {{ $model->customer_id == $customer->id ? 'selected' : null }}>{{ $customer->firstname }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        @endif

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
                            <label class="control-label" for="BankInvoiceDetail_bank_account_id">Conta</label>
                            <select name="BankInvoiceDetail[bank_account_id]" id="BankInvoiceDetail_bank_account_id" class="select2">
                                @if($bankAccounts)
                                    @foreach($bankAccounts as $bankAccount)
                                        <option value="{{ $bankAccount->id }}" {{ $model->bank_account_id == $bankAccount->id ? 'selected' : null }}>{{ $bankAccount->name }}</option>
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

                        <!--
                        <div class="row mb-10">
                            <div class="col-sm-6">
                                <label class="vcheck m-0">
                                    <input type="checkbox" class="checkbox style-0" name="BankInvoiceTransaction[fixed]" id="BankInvoiceTransaction_fixed" value="1" {{ !empty($model->fixed) ? 'checked' : null }} >
                                    <span>Fixa</span>
                                </label>
                            </div>
                            <div class="col-sm-6"></div>
                        </div>
                        -->

                        <div class="row mb-10">
                            <div class="col-sm-4" style="margin-top: 8px">
                                <label class="vcheck m-0">
                                    <input type="checkbox" class="checkbox style-0" name="BankInvoiceTransaction[repeat]" id="BankInvoiceTransaction_repeat" value="1" {{ !empty($model->repeat) ? 'checked' : null }} >
                                    <span>Repetir</span>
                                </label>
                            </div>
                            <div class="col-sm-4">
                                <input type="text" name="BankInvoiceTransaction[total_invoice]" id="BankInvoiceTransaction_total_invoice" class="mask-number form-control" value="1" disabled />
                            </div>
                            <div class="col-sm-4">
                                <select name="BankInvoiceTransaction[type_cycle]" id="BankInvoiceTransaction_type_cycle" class="select2" disabled>
                                    <option>Selecione</option>
                                    @foreach($cycles as $key => $cycle)
                                        <option value="{{ $key }}" {{ $model->type_cicle == $key ? 'selected' : null }}>{{ $cycle }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row mb-10">
                            <div class="col-sm-4">
                                <label class="vcheck m-0">
                                    <input type="checkbox" class="checkbox style-0" name="BankInvoiceDetail[status]" id="BankInvoiceDetail_status" value="3" {{ ($model->status == 3) ? 'checked' : null }} >
                                    <span>Esta pago</span>
                                </label>
                            </div>
                            <div class="col-sm-4">
                                <input type="text" name="BankInvoiceDetail[payment_date]" id="BankInvoiceDetail_payment_date" class="form-control datepicker" value="" disabled />
                            </div>
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
