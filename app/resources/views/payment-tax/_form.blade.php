<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">

        <div class="modal-header">
            <h4 class="modal-title">Teste</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                <span aria-hidden="true">×</span>
            </button>
        </div>

        <form id="form-payment-tax" action=''>
            @csrf
            <div class="modal-body">
                <input type="hidden" name="PaymenTax[payment_method_id]" value="" />

               @if (!in_array($payment_method_id, [Sisnanceiro\Models\PaymentMethod::TRANSFER, Sisnanceiro\Models\PaymentMethod::BANK_DRAFT] ))
                <div class="row">
                    <div class="col-sm-4">
                        <label class="control-label">Nome</label>
                        <input type="text" class="form-control" name="PaymentMethod[name]" id="PaymentMethod_name" value="" />
                    </div>
                    <div class="col-sm-4">
                        <label class="control-label">Dias úteis</label>
                        <input type="text" class="form-control mask-number" name="PaymentMethod[days_business]" id="PaymentMethod_days_business" value="" />
                    </div>
                </div>
                @endif

                @if (!in_array($payment_method_id, [Sisnanceiro\Models\PaymentMethod::CREDIT_CARD, Sisnanceiro\Models\PaymentMethod::DEBIT_CARD] ))
                <div class="row">
                    <div class="col-sm-4">
                        <label class="control-label" for="category-allow_block">Dias úteis</label>
                        <input type="checkbox" name="PaymentMethod[days_business]" class="form-control mask-number" />
                    </div>
                </div>
                @endif

                <div class="row">
                    <div class="form-group">
                       <select name="PaymentMethod[bank_account_id]" class="form-control selectpicker" id="PaymentMethod_bank_account_id" title="Todas as contas" multiple>
                            @if($bankAccounts)
                                @foreach($bankAccounts as $bankAccount)
                                    <option value="{{ $bankAccount->id }}">{{ $bankAccount->name }}</option>
                                @endforeach
                            @endif
                       </select>
                    </div>
                </div>

                <div class="">
                    @switch($payment_method_id)
                        @case (Sisnanceiro\Models\PaymentMethod::CREDIT_CARD)
                            @include('/payment-tax/_form_credit-card')
                            @break
                        @case (Sisnanceiro\Models\PaymentMethod::TRANSFER)
                        @case (Sisnanceiro\Models\PaymentMethod::BANK_DRAFT)
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
