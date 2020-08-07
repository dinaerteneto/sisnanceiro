<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">

        <div class="modal-header">
            <h4 class="modal-title">{{ Sisnanceiro\Models\PaymentMethod::get($model->payment_method_id) }}</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                <span aria-hidden="true">×</span>
            </button>
        </div>

        <?php $formAction = !empty($model->id) ? "/payment-tax/update/$model->id" : "/payment-tax/create/$model->payment_method_id";?>

        <form id="form-payment-tax" method="post" action="{{ $formAction }}">
            @csrf
            <input type="hidden" name="PaymentTax[id]" value="{{ $model->id }}" />
            <div class="modal-body">
                <input type="hidden" name="PaymentTax[payment_method_id]" value="{{ $model->payment_method_id }}" />

                @if($bankAccounts)
                <div class="row mb-10">
                    <div class="col-sm-12">
                        <label class="control-label" for="PaymentMethod_bank_account_id">Conta</label>
                        <select name="PaymentTax[bank_account_id]" class="form-control bank_account_id" id="PaymentMethod_bank_account_id" title="Todas as contas">
                            <option value="">-- Selecione --</option>
                            @foreach($bankAccounts as $bankAccount)
                                <option value="{{ $bankAccount->id }}" {{ $model->bank_account_id == $bankAccount->id ? 'selected' : null }}>{{ $bankAccount->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                @endif

                <div class="row mb-10">
                    <div class="col-sm-4">
                        <label class="control-label">Nome</label>
                        <input type="text" class="form-control name" name="PaymentTax[name]" id="PaymentMethod_name" value="{{ $model->name }}" />
                    </div>

                    <div class="col-sm-4">
                        <label class="control-label">Dias para pagamento</label>
                        <input type="text" class="form-control mask-number days_for_payment" name="PaymentTax[days_for_payment]" id="PaymentMethod_days_for_payment" value="{{ $model->days_for_payment }}" />
                    </div>

                    @if (!in_array($model->payment_method_id, [Sisnanceiro\Models\PaymentMethod::CREDIT_CARD, Sisnanceiro\Models\PaymentMethod::DEBIT_CARD] ))
                        <div class="col-sm-4">
                            <br />
                            <label class="vcheck m-0">
                                <input type="checkbox" name="PaymentTax[days_business]" class="checkbox style-0" value="{{ $model->days_business }}" />
                                <span>Dias úteis</span>
                            </label>
                        </div>
                    @endif
                </div>

                <div class="">
                    @switch($model->payment_method_id)
                        @case (Sisnanceiro\Models\PaymentMethod::CREDIT_CARD)
                            @include('/payment-tax/_form_credit-card', compact('model'))
                            @break
                        @case (Sisnanceiro\Models\PaymentMethod::TRANSFER)
                        @case (Sisnanceiro\Models\PaymentMethod::BANK_DRAFT)
                            @include('/payment-tax/_form_partial_null', compact('model'))
                            @break
                        @default
                            @include('/payment-tax/_form_partial', compact('model'))
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
