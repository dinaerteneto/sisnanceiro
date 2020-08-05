<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">

        <div class="modal-header">
            <h4 class="modal-title">Teste</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                <span aria-hidden="true">×</span>
            </button>
        </div>

        <form id="form-payment-tax" method="post" action="/payment-tax/create/{{ $payment_method_id }}">
            @csrf
            <div class="modal-body">
                <input type="hidden" name="PaymentTax[payment_method_id]" value="{{ $payment_method_id }}" />

                @if($bankAccounts)
                <div class="row mb-10">
                    <div class="col-sm-12">
                        <label class="control-label" for="PaymentMethod_bank_account_id">Conta</label>
                        <select name="PaymentTax[bank_account_id]" class="form-control bank_account_id" id="PaymentMethod_bank_account_id" title="Todas as contas">
                            <option value="">-- Selecione --</option>
                            @foreach($bankAccounts as $bankAccount)
                                <option value="{{ $bankAccount->id }}">{{ $bankAccount->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                @endif

                <div class="row mb-10">
                    <div class="col-sm-4">
                        <label class="control-label">Nome</label>
                        <input type="text" class="form-control name" name="PaymentTax[name]" id="PaymentMethod_name" value="" />
                    </div>

                    <div class="col-sm-4">
                        <label class="control-label">Dias para pagamento</label>
                        <input type="text" class="form-control mask-number days_for_payment" name="PaymentTax[days_for_payment]" id="PaymentMethod_days_for_payment" value="" />
                    </div>

                    @if (!in_array($payment_method_id, [Sisnanceiro\Models\PaymentMethod::CREDIT_CARD, Sisnanceiro\Models\PaymentMethod::DEBIT_CARD] ))
                        <div class="col-sm-4">
                            <br />
                            <label class="vcheck m-0">
                                <input type="checkbox" name="PaymentTax[days_business]" class="checkbox style-0" value="1" />
                                <span>Dias úteis</span>
                            </label>
                        </div>
                    @endif
                </div>

                <div class="">
                    @switch($payment_method_id)
                        @case (Sisnanceiro\Models\PaymentMethod::CREDIT_CARD)
                            @include('/payment-tax/_form_credit-card')
                            @break
                        @case (Sisnanceiro\Models\PaymentMethod::TRANSFER)
                        @case (Sisnanceiro\Models\PaymentMethod::BANK_DRAFT)
                            @include('/payment-tax/_form_partial_null')
                            @break
                        @default
                            @include('/payment-tax/_form_partial')
                            @break
                    @endswitch
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Enviar</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            </div>

        </form>
    </div>
</div>
@include('layouts/_partial_scripts')
