<div class="row">
    <div class="col-sm-12 form-group">
        <a id="btn-payment-tax-create"
            class="btn btn-success open-modal pull-right"
            href="/payment-tax/create/{{ $payment_method_id }}"
            data-modal-title="Incluir"
            data-payment-method="{{ $payment_method_id }}"
            data-is-modal="true" data-stop-event="true"
            target="#remoteModal"
        >INCLUIR
        </a>
    </div>
</div>

<div class="row">
    <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12 sortable-grid ui-sortable">
        <div class="jarviswidget well jarviswidget-color-darken">
            <div class="widget-body no-padding">
                <div class="dataTables_wrapper dt-bootstrap4 no-footer">
                    <table id="dt_basic_{{ $payment_method_id }}" class="table table-striped table-bordered table-hover" width="100%">
                        <thead>
                            <tr>
                                <th width="30%">Nome</th>
                                <th width="20%">Conta</th>
                                <th width="20%">Dias para recebimento (D + X)</th>
                                <th width="20%">Dias úteis</th>
                                <th width="10%">Ações</th>
                            </tr>
                        </thead>
                    </table>
                </div>

            </div>
        </div>
    </article>
</div>
