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
                            <label class="control-label" for="BankInvoiceDetail_due_date">Data da transferência</label>
                            <input type="text" name="BankInvoiceDetail[due_date]" id="BankInvoiceDetail_due_date" class="form-control datepicker mask-date" value="{{ $model->due_date }}" />
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
                            <label class="control-label" for="BankInvoiceDetail_bank_account_id_source">Conta de origem</label>
                            <select name="BankInvoiceDetail[bank_account_id_source]" id="BankInvoiceDetail_bank_account_id_source" class="select2">
                                @if($bankAccounts)
                                    @foreach($bankAccounts as $bankAccount)
                                        <option value="{{ $bankAccount->id }}" {{ $model->bank_account_id == $bankAccount->id ? 'selected' : null }}>{{ $bankAccount->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <label class="control-label" for="BankInvoiceDetail_bank_account_id_destiny">Conta de destino</label>
                            <select name="BankInvoiceDetail[bank_account_id_destiny]" id="BankInvoiceDetail_bank_account_id_destiny" class="select2">
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
