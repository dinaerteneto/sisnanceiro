<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <form id="bank-transaction-form" class="bank-transaction-form" method="post" action="/bank-transaction/partial-pay/{{ $model->id }}" onsubmit="return false">
            @csrf

               <div class="modal-header">
                <h4 class="modal-title">Pagamento parcial</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">×</span>
                </button>

            </div>
            <div class="modal-body">
                <fieldset>

                    <div class="row mb-10">
                        Cartão: {{ $creditCard->name }} <br />
                        Dia vencto da fatura: {{ $model->due_date }} <br />
                        Valor total: {{ $model->net_value }}
                    </div>

                    <div class="row mb-10">
                        <div class="col-sm-3">
                            <label class="control-label" for="BankInvoiceDetail_net_value">Valor a ser pago</label>
                            <input type="text" name="BankInvoiceDetail[net_value]" id="BankInvoiceDetail_net_value" class="form-control mask-currency" />
                        </div>
                        <div class="col-sm-3">
                            <label class="control-label" for="BankInvoiceDetail_payment_date">Data do pagto</label>
                            <input type="text" name="BankInvoiceDetail[payment_date]" id="BankInvoiceDetail_payment_date" class="form-control datepicker"  />
                        </div>
                    </div>

                    <div class="row mb-10">
                        <div class="col-sm-6">
                            <label class="control-label" for="BankInvoiceDetail_bank_account_id">Conta</label>
                            <select name="BankInvoiceDetail[bank_account_id]" id="BankInvoiceDetail_bank_account_id" class="select2">
                                @if($bankAccounts)
                                    @foreach($bankAccounts as $bankAccount)
                                        <option value="{{ $bankAccount->id }}" {{ $creditCard->bank_account_id == $bankAccount->id ? 'selected' : null }}>{{ $bankAccount->name }}</option>
                                    @endforeach
                                @endif
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


@include('layouts/_partial_scripts')
<script type="text/javascript" src="{{ asset('assets/js/custom/BankTransaction.js') }}"></script>
<script type="text/javascript">

</script>
