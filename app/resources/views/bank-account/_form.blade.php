<div class="modal-dialog modal-lg" role="document">

    <div class="modal-content">

        <form id="bank-account-form" class="bank-category-form" method="post" action="{{ $action }}">
        @csrf

        <div class="modal-header">
            <h4 class="modal-title">{{ $title }}</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                <span aria-hidden="true">×</span>                
            </button>
            
        </div>
        <div class="modal-body">
            <fieldset>
                <legend>Dados básicos</legend>
                <div class="row">
                    <div class="col-sm-8">
                        <div class="form-group">
                            <label class="control-label" for="BankAccount_name">Nome da conta</label>
                            <input type="text" name="BankAccount[name]" value="{{ $model->name }}" id="BankAccount_name" class="form-control" />
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="control-label" for="BankAccount_type">Tipo de pessoa</label>
                            <select name="BankAccount[physical]" id="BankAccount_physical" class="form-control select2">
                                <option value="0" {{$model->physical != 1 ? 'selected' : null}}>Jurídica</option>
                                <option value="1" {{$model->physical == 1 ? 'selected' : null}} >Física</option>
                            </select>
                        </div>
                    </div>    
                </div>

                <div class="row">
                    <div class="col-sm-8">
                        <label class="control-label" for="BankAccount_legal_name">Razão social</label>
                        <input type="BankAccount[legal_name]" id="BankAccount_legal_name" class="form-control" value="{{ $model->legal_name }}" />
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="control-label" for="BankAccount_cpf_cnpj">CNPJ</label>
                            <input type="text" name="BankAccount[cpf_cnpj]" value="{{ $model->cpf_cnpj }}" id="BankAccount_cpf_cnpj" class="form-control mask-cnpj" />
                        </div>
                    </div>            
                </div>

                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="control-label" for="BankAccount_initial_balance">Saldo inicial</label>
                            <input type="text" name="BankAccount[initial_balance]" value="{{ $model->initial_balance }}" id="BankAccount_initial_balance" class="form-control mask-currency" />
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="control-label" for="BankAccount_initial_balance_date">Data do saldo</label>
                            <input type="text" name="BankAccount[initial_balance_date]" value="{{ $model->initial_balance_date }}" id="BankAccount_initial_balance_date" class="form-control mask-date datepicker" />
                        </div>
                    </div>
                </div>

            </fieldset>

            <fieldset>
                <legend>Dados bancários</legend>
                <div class="row mb-10">
                    <div class="col-sm-6 vcheck">
                        <label>
                            <input type="checkbox" class="checkbox style-0" name="BankAccount[send_bank_account]" id="bankaccount_send_bank_account" {{ !empty($model->send_bank_account) ? 'checked' : null }} >
                            <span>Preencher dados bancários</span>
                        </label>
                    </div>
                    <div class="col-sm-6 vcheck">
                        <label>
                            <input type="checkbox" class="checkbox style-0" name="BankAccount[default]" id="BankAccount_default" value="1" {{ !empty($model->default) ? 'checked' : null }} />
                            <span>Conta padrão</span>
                        </label>
                    </div>
                </div>

                <div id="data-account" class="d-none">
                    <div class="row mb-10">
                        <div class="col-sm-6">
                            <label class="control-label" for="BankAccount_bank_id">Banco</label>
                            <select name="BankAccount[bank_id]" id="BankAccount_bank_id" class="form-control select2">
                                <option value="">Selecione</option>
                                @if(isset($banks))
                                    @foreach($banks as $bank)
                                        <option value="{{ $bank->id }}"  {{$model->bank_id == $bank->id ? 'selected' : null}}  >{{ $bank->title }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <label class="control-label">Tipo de conta</label>
                            <select name="BankAccount[type]" id="BankAccount_type" class="form-control select2">
                                <option value="">Selecione</option>
                                @foreach($types as $key => $type)
                                    <option value="{{ $key }}" {{ $model->type == $key ? 'selected' : null }}>{{ $type }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-4">
                            <label class="control-label" for="BankAccount_agency">Agência</label>
                            <input type="text" name="BankAccount[agency]" id="BankAccount_agency" class="form-control mask-number" value="{{ $model->agency }}" />
                        </div>
                        <div class="col-sm-2">
                            <label class="control-label" for="BankAccount_agency_dv">Agência DV</label>
                            <input type="text" name="BankAccount[agency_dv]" id="BankAccount_agency_dv" class="form-control mask-number" value="{{ $model->agency_dv }}" />
                        </div>
                        <div class="col-sm-4">
                            <label class="control-label" for="BankAccount_account">Conta</label>
                            <input type="text" name="BankAccount[account]" id="BankAccount_account" class="form-control mask-number" value="{{ $model->account }}" />
                        </div>
                        <div class="col-sm-2">
                            <label class="control-label" for="BankAccount_account_dv">Conta DV</label>
                            <input type="text" name="BankAccount[account_dv]" id="BankAccount_account_dv" class="form-control mask-number" value="{{ $model->account_dv }}" />
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


<script type="text/javascript">
$('document').ready(function(){

    BankAccount.requiredDataAccount();

    var validForm = $('#bank-account-form').validate({
        ignore: null,
        rules: {
            'BankAccount[name]': 'required',
            'BankAccount[physical]': 'required',
            'BankAccount[initial_balance]': 'required',
            'BankAccount[initial_balance_date]': 'required',

            'BankAccount[bank_id]': {
                required: function(element) {
                    return $('#bankaccount_send_bank_account').is(':checked')
                }
            },
            'BankAccount[type]': {
                required: function(element) {
                    return $('#bankaccount_send_bank_account').is(':checked')
                }
            },
            'BankAccount[agency]': {
                required: function(element) {
                    return $('#bankaccount_send_bank_account').is(':checked')
                }
            },
            'BankAccount[agency_dv]': {
                required: function(element) {
                    return $('#bankaccount_send_bank_account').is(':checked')
                }
            },
            'BankAccount[account]': {
                required: function(element) {
                    return $('#bankaccount_send_bank_account').is(':checked')
                }
            },
            'BankAccount[account_dv]': {
                required: function(element) {
                    return $('#bankaccount_send_bank_account').is(':checked')
                }
            }
        },
        messages: {
            'BankAccount[name]': 'Obrigatório',
            'BankAccount[physical]': 'Obrigatório',
            'BankAccount[initial_balance]': 'Obrigatório',
            'BankAccount[initial_balance_date]': 'Obrigatório',
            'BankAccount[bank_id]': {
                required: 'Obrigatório'
            },
            'BankAccount[type]': {
                required: 'Obrigatório'
            },
            'BankAccount[agency]': {
                required: 'Obrigatório'
            },
            'BankAccount[agency_dv]': {
                required: 'Obrigatório'
            },
            'BankAccount[account]': {
                required: 'Obrigatório'
            },
            'BankAccount[account_dv]': {
                required: 'Obrigatório'
            },
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
