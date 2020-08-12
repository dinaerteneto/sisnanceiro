<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <form id="bank-transaction-form-delete" class="bank-transaction-form" method="post" action="{{ $action }}">
            <input type="hidden" name="BankInvoiceTransaction[id]" value="{{ $model->bank_invoice_transaction_id }}" />
            <input type="hidden" name="BankInvoiceDetail[id]" value="{{ $model->id }}" />
            @csrf

            <div class="modal-header">
                <h4 class="modal-title">Tem certeza que deseja excluir este lançamento?</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">×</span>
                </button>

            </div>
            <div class="modal-body">
                <fieldset>
                    <div class="row  mb-10">
                        <div class="col-sm-4">
                            <label class="form-label">Valor</label><br>
                            R$ {{ $model->net_value }}
                        </div>
                        <div class="col-sm-4">
                            <label class="form-label">Data vencto</label><br>
                            {{ $model->due_date }}
                        </div>
                        <div class="col-sm-4">
                            <label class="form-label">Categoria</label><br>
                            {{ $model->category_name }}
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <label class="form-label">Descrição</label>  <br>
                            {{ $model->description }}
                        </div>
                    </div>
                </fieldset>

                @if($model->total_invoices > 1)
                <fieldset>
                    <div class="row">
                        <div class="col-sm-12 text-center font-italic">
                            <strong>Atenção! Este é um lançamento repetido</strong>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <label class="form-label">Você deseja excluir: *</label>
                        </div>
                    </div>

                    <div class="row mb-10">
                        <div class="col-sm-12">
                            <label class="radio radio-inline">
                                <input type="radio" class="radiobox BankInvoiceTransaction_option_update" name="BankInvoiceTransaction[option_delete]" value="1">
                                <span>Somente este</span>
                            </label>
                            <label class="radio radio-inline">
                                <input type="radio" class="radiobox BankInvoiceTransaction_option_update" name="BankInvoiceTransaction[option_delete]" value="4">
                                <span>Todos pendentes</span>
                            </label>
                            <label class="radio radio-inline">
                                <input type="radio" class="radiobox BankInvoiceTransaction_option_update" name="BankInvoiceTransaction[option_delete]" value="3">
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
                <input type="hidden" value="1" name="BankInvoiceTransaction[option_delete]" />
                @endif

            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Excluir</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            </div>

        </form>
    </div>
</div>

@section('scripts')
@include('layouts/_partial_scripts')
<script type="text/javascript">
    $('#bank-transaction-form-delete').on('submit', function(e) {
        e.preventDefault();
        var value = 10;
        if($('.BankInvoiceTransaction_option_update').length > 0) {
            var value = $('.BankInvoiceTransaction_option_update:checked').val();
        }
        if(isNaN(value)) {
            swal("Oops...", "Você deve selecionar uma opção", "error");
            return false;
        } else {

            var url = $(this).attr('action');
            var data = $(this).serialize();
            $.post(url, data, function(json) {
                if (json.success) {
                    $('#btn-search').trigger('click');
                    $('#remoteModal').modal('hide');
                    $.smallBox({
                        title: "Sucesso!",
                        content: "<i class='fa fa-clock-o'></i> <i>Lançamento(s) removido(s) com sucesso!</i>",
                        color: "#659265",
                        iconSmall: "fa fa-check fa-2x fadeInRight animated",
                        timeout: 2000
                    });
                } else {
                    swal("Oops...", "Ocorreu algum erro!!!.", "error");
                }
            }, 'json');
        }
    });
</script>
