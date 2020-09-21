<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <form id="bank-transaction-form" class="bank-transaction-form" method="post" action="{{ $action }}" onsubmit="return false">
            <input type="hidden" name="BankInvoiceTransaction[id]" value="{{ $model->bank_invoice_transaction_id }}" />
            <input type="hidden" name="BankInvoiceDetail[id]" value="{{ $model->id }}" />

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
                            <input type="text" name="BankInvoiceDetail[net_value]" id="BankInvoiceDetail_net_value" class="form-control mask-currency" value="{{ $model->net_value }}" readonly="readonly" />
                        </div>
                        <div class="col-sm-4">
                            <label class="control-label" for="BankInvoiceDetail_competence_date">Data da compra</label>
                            <input type="text" name="BankInvoiceDetail[competence_date]" id="BankInvoiceDetail_competence_date" class="form-control" value="{{ $model->competence_date }}" readonly="readonly" />
                        </div>
                        <div class="col-sm-4">
                            <label class="control-label" for="BankInvoiceDetail_due_date">Para vencto em</label>
                            <input type="text" name="BankInvoiceDetail[due_date]" id="BankInvoiceDetail_due_date" class="form-control" value="{{ $model->due_date }}" readonly="readonly" />
                        </div>
                    </div>

                    <div class="row mb-10">
                        <div class="col-sm-4">
                            <label class="control-label" for="BankInvoiceDetail_supplier_id">Fornecedor</label>
                            <select name="BankInvoiceDetail[supplier_id]" id="BankInvoiceDetail_supplier_id" class="select2">
                                @if($suppliers)
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}" {{ $model->supplier_id == $supplier->id ? 'selected' : null }}>{{ $supplier->firstname }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <label class="control-label" for="BankInvoiceDetail_bank_category_id">Categoria</label>
                            <input name="BankInvoiceDetail[bank_category_id]" id="BankInvoiceDetail_bank_category_id" value="{{ $model->bank_category_id }}" class="control-form">
                        </div>
                        <div class="col-sm-4">
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
                            <label class="control-label" for="BankInvoiceTransaction_description">Descrição</label>
                            <textarea name="BankInvoiceTransaction[description]" id="BankInvoiceTransaction_description" class="form-control">{{$model->description}}</textarea>
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
