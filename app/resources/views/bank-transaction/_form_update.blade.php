<?php
use Sisnanceiro\Models\BankCategory;
?>

<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <form id="bank-transaction-form" class="bank-transaction-form" method="post" action="{{ $action }}">
            <input type="hidden" name="BankInvoiceTransaction[id]" value="{{ $model->bank_invoice_transaction_id }}" />
            <input type="hidden" name="BankInvoiceDetail[id]" value="{{ $model->id }}" />
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
                        <div class="col-sm-3">
                            <label class="control-label" for="BankInvoiceDetail_net_value">Insira o valor</label>
                            <input type="text" name="BankInvoiceDetail[net_value]" id="BankInvoiceDetail_net_value" class="form-control mask-currency" data-original-value="{{ $model->net_value }}" value="{{ $model->net_value }}" />
                        </div>
                        <div class="col-sm-3">
                            <label class="control-label" for="BankInvoiceDetail_due_date">Data de vencto</label>
                            <input type="text" name="BankInvoiceDetail[due_date]" id="BankInvoiceDetail_due_date" data-original-value="{{ $model->due_date }}" class="form-control datepicker" value="{{ $model->due_date }}" />
                        </div>
                        @if($mainCategory['main_category_id'] == BankCategory::CATEGORY_TO_PAY)
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
                            <input name="BankInvoiceDetail[bank_category_id]" id="BankInvoiceDetail_bank_category_id" value="{{ $model->bank_category_id }}" class="control-form">
                        </div>
                        <div class="col-sm-6">
                            <label class="control-label" for="BankInvoiceDetail_bank_account_id">Conta</label>
                            <select name="BankInvoiceDetail[bank_account_id]" id="BankInvoiceDetail_bank_account_id" class="select2" data-original-value="{{ $model->bank_account_id }}">
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
                        <div class="col-sm-4">
                            <label class="vcheck m-0">
                                <input type="checkbox" class="checkbox style-0" name="BankInvoiceDetail[status]" id="BankInvoiceDetail_status" value="3" {{ ($model->status == 3) ? 'checked' : null }} >
                                <span>Esta pago</span>
                            </label>
                        </div>      
                        <div class="col-sm-4">
                            <input type="text" name="BankInvoiceDetail[payment_date]" id="BankInvoiceDetail_payment_date" class="form-control datepicker" value="{{ $model->payment_date }}" data_original_value="{{ $model->payment_date }}" {{ ($model->status != 3) ? 'disabled' : null }} />
                        </div>                                            
                    </div>

                </fieldset>

                @if($model->total_invoices > 1) 
                <fieldset>
                    <div class="row">
                        <div class="col-sm-12 text-center font-italic">
                            <strong>Atenção! Esta é um lançamento repetido {{ $model->description }} ({{ $model->parcel_number }}/{{ $model->total_invoices }}) </strong>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <label class="form-label">Você deseja editar: *</label>
                        </div>
                    </div>

                    <div class="row mb-10">
                        <div class="col-sm-12">
                            <label class="radio radio-inline">
                                <input type="radio" class="radiobox BankInvoiceTransaction_option_update" name="BankInvoiceTransaction[option_update]" value="1">
                                <span>Somente este</span>                                    
                            </label>
                            <label class="radio radio-inline">
                                <input type="radio" class="radiobox BankInvoiceTransaction_option_update" name="BankInvoiceTransaction[option_update]" value="2">
                                <span>Este, e os futuros</span>                                    
                            </label>
                            <label class="radio radio-inline">
                                <input type="radio" class="radiobox BankInvoiceTransaction_option_update" name="BankInvoiceTransaction[option_update]" value="3">
                                <span>Todos (incluíndo efetivados)</span>                                    
                            </label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="alert alert-warning alert-dismissible fade show d-none" id="msg-option">
                            </div>
                        </div>
                    </div>
                </fieldset>
                @else
                <input type="hidden" value="1" name="BankInvoiceTransaction[option_update]" />
                @endif

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
var data = {!! $categoryOptions !!};

$('document').ready(function() {

    $('#bank-transaction-form').on('submit', function(e) {
        if($('.BankInvoiceTransaction_option_update').length > 0) {
            var value = $('.BankInvoiceTransaction_option_update:checked').val();
            if(isNaN(value)) {
                swal("Oops...", "Você deve selecionar uma opção", "error");
                return false;
            } else {
                $('#bank-transaction-form').submit();
            }
        }
    });

    $('#BankInvoiceDetail_status').on('click', function() {
        $('#BankInvoiceDetail_payment_date').val($('#BankInvoiceDetail_payment_date').attr('data-original-value'));
        if($(this).is(':checked')) {
            $('#BankInvoiceDetail_payment_date').prop('disabled', false);
        }
        else {
            $('#BankInvoiceDetail_payment_date').prop('disabled', true);
        }
    });

    $('.BankInvoiceTransaction_option_update').on('change', function(e) {
        e.preventDefault();
        $('#BankInvoiceDetail_due_date').val( $('#BankInvoiceDetail_due_date').attr('data-original-value') );
        $('#BankInvoiceDetail_net_value').val( $('#BankInvoiceDetail_net_value').attr('data-original-value') );
        $('#BankInvoiceDetail_status').val( $('#BankInvoiceDetail_status').attr('data-original-value') );
        $('#BankInvoiceDetail_status').trigger('change');
        $('#BankInvoiceDetail_bank_account_id').val( $('#BankInvoiceDetail_bank_account_id').attr('data-original-value') );
        $('#BankInvoiceDetail_bank_account_id').trigger('change');

        $('#BankInvoiceDetail_due_date').attr('disabled', false);
        $('#BankInvoiceDetail_status').attr('disabled', false);
        $('#BankInvoiceDetail_bank_account_id').attr('disabled', false);
        $('#BankInvoiceDetail_net_value').attr('disabled', false);
        $('#msg-option').addClass('d-none');

        var value = $('.BankInvoiceTransaction_option_update:checked').val();
        var msg = '';

        switch (value) {
            case '2':
                $('#msg-option').removeClass('d-none');
                $('#BankInvoiceDetail_due_date').attr('disabled', true);
                $('#BankInvoiceDetail_status').attr('disabled', true);
                msg = '<i class="fa-fw fa fa-warning"></i> Não é possível alterar a data ou efetivar a despesa.';
                break;
            case '3':
                $('#BankInvoiceDetail_due_date').attr('disabled', true);
                $('#BankInvoiceDetail_status').attr('disabled', true);
                $('#BankInvoiceDetail_bank_account_id').attr('disabled', true);
                $('#BankInvoiceDetail_net_value').attr('disabled', true);
                $('#msg-option').removeClass('d-none');
                msg = '<i class="fa-fw fa fa-warning"></i> Não é possível alterar o Valor, Data, Conta ou Efetivar a despesa.';
                break;
        }

        $('#msg-option').html(msg);
    });
})  
</script>
<script type="text/javascript" src="{{ asset('assets/js/custom/BankTransaction.js') }}"></script>