<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <form id="bank-transaction-transfer-form" class="bank-transaction-form" method="post" action="/bank-transaction/transfer/create">
            @csrf

            <input type="hidden" name="BankInvoiceDetail[bank_category_id]" value="{{ Sisnanceiro\Models\BankCategory::CATEGORY_TRANSFER }}" />

            <div class="modal-header">
                <h4 class="modal-title">Nova transferência</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">×</span>
                </button>

            </div>
            <div class="modal-body">
                <fieldset>
                    <div class="row mb-10">
                        <div class="col-sm-6">
                            <label class="control-label" for="BankInvoiceDetail_net_value">Insira o valor</label>
                            <input type="text" name="BankInvoiceDetail[net_value]" id="BankInvoiceDetail_net_value" class="form-control mask-currency" value="{{ $model->net_value }}" />
                        </div>
                        <div class="col-sm-6">
                            <label class="control-label" for="BankInvoiceDetail_due_date">Data de vencto</label>
                            <input type="text" name="BankInvoiceDetail[due_date]" id="BankInvoiceDetail_due_date" class="form-control datepicker" value="{{ $model->due_date }}" />
                        </div>
                    </div>

                    <div class="row mb-10">
                        <div class="col-sm-6">
                            <label class="control-label" for="BankInvoiceDetail_bank_account_source_id">Conta de origem</label>
                            <select name="BankInvoiceDetail[bank_account_source_id]" id="BankInvoiceDetail_bank_account_source_id" class="select2">
                                @if($bankAccounts)
                                    @foreach($bankAccounts as $bankAccount)
                                        <option value="{{ $bankAccount->id }}">{{ $bankAccount->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <div class="col-sm-6">
                            <label class="control-label" for="BankInvoiceDetail_bank_account_target_id">Conta de destino</label>
                            <select name="BankInvoiceDetail[bank_account_target_id]" id="BankInvoiceDetail_bank_account_target_id" class="select2">
                                @if($bankAccounts)
                                    @foreach($bankAccounts as $bankAccount)
                                        <option value="{{ $bankAccount->id }}">{{ $bankAccount->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>

                    <div class="row mb-10">
                        <div class="col-sm-12">
                            <label class="control-label" for="BankInvoiceTransaction_description">Observações</label>
                            <textarea name="BankInvoiceTransaction[note]" id="BankInvoiceTransaction_description" class="form-control">{{$model->note}}</textarea>
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
<script type="text/javascript">
$('document').ready(function(){

    var validForm = $('#bank-transaction-transfer-form').validate({
        ignore: null,
        rules: {
            'BankInvoiceDetail[net_value]': {
                required: true,
                greaterThanZero: true
            },
            'BankInvoiceDetail[due_date]': 'required',
            'BankInvoiceDetail[bank_account_source_id]': 'required',
            'BankInvoiceDetail[bank_account_target_id]': 'required',

            'BankInvoiceDetail[bank_account_source_id]': {
                required: function(element) {
                    return $('#bankinvoicedetail_bank_account_source_id').is(':checked')
                }
            },
            'BankInvoiceDetail[bank_account_target_id]': {
                required: function(element) {
                    return $('#bankinvoicedetail_bank_account_target_id').is(':checked')
                }
            },

        },
        messages: {
            'BankInvoiceDetail[net_value]': {
                required: 'Obrigatório',
                greaterThanZero: 'Deve ser maior que zero'
            },
            'BankInvoiceDetail[due_date]': 'Obrigatório',
            'BankInvoiceDetail[bank_account_source_id]': 'Obrigatório',
            'BankInvoiceDetail[bank_account_target_id]': 'Obrigatório',
            'BankInvoiceDetail[bank_source_id]': {
                required: 'Obrigatório'
            },
            'BankInvoiceDetail[bank_account_target_id]': {
                required: 'Obrigatório'
            }
        },
        highlight: function(element) {
            $(element).removeClass('is-valid').addClass('is-invalid');
            $(element).closest('.form-group').removeClass('has-success').addClass('has-error');
        },
        unhighlight: function(element) {
            $(element).removeClass('is-invalid').addClass('is-valid');
            $(element).closest('.form-group').removeClass('has-error').addClass('has-success');
        },
        errorElement: 'span',
        errorClass: 'invalid-feedback',
        errorPlacement: function(error, element) {
            if (element.parent('.input-group').length) {
                error.insertAfter(element.parent());
            } else {
                error.insertAfter(element);
            }
        }
    });

})
</script>