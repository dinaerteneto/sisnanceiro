@extends('layouts.app')

@section('content')

<div class="d-flex w-100 home-header">
    <div>
        <h1 class="page-header"><i class="fa fa-table fa-fw "></i> Financeiro <span>&gt; Taxas e prazos</span></h1>
    </div>
</div>

<article class="col-12 sortable-grid">
    <div class="jarviswidget jarviswidget-style-2 no-padding">
        <div role="content">
            <div class="jarviswidget jarviswidget-color-darken">
                <header role="heading">
                    <div class="widget-header">
                        <span class="widget-icon">
                            <i class="fa fa-lock"></i>
                        </span>
                        <h2><strong>Nome do banco</strong></h2>
                    </div>
                    <span class="ml-auto" role="menu"></span>
                </header>
                <div role="content">
                    <div class="widget-body">
                        <ul class="nav nav-tabs bordered" id="tab-payment-tax">
                           <li class="active">
                                <a data-toggle="tab" href="#credit-card">Cartão de crédito</a>
                            </li>
                            <li class="">
                                <a data-toggle="tab" href="#debit-card">Cartão de débito</a>
                            </li>
                            <li class="">
                                <a data-toggle="tab" href="#order">Boleto</a>
                            </li>
                            <li class="">
                                <a data-toggle="tab" href="#transfer">Transferência</a>
                            </li>
                            <li class="">
                                <a data-toggle="tab" href="#bank-draft">Cheque</a>
                            </li>
                        </ul>
                        <div class="tab-content padding-10">
                            <div id="credit-card" class="tab-pane active">
                                @include('/payment-tax/_list_payment_methods', ['payment_method_id' => Sisnanceiro\Models\PaymentMethod::CREDIT_CARD])
                            </div>
                            <div id="debit-card" class="tab-pane">
                                @include('/payment-tax/_list_payment_methods', ['payment_method_id' => Sisnanceiro\Models\PaymentMethod::DEBIT_CARD])
                            </div>
                            <div id="order" class="tab-pane">
                                @include('/payment-tax/_list_payment_methods', ['payment_method_id' => Sisnanceiro\Models\PaymentMethod::ORDER])
                            </div>
                            <div id="transfer" class="tab-pane">
                                @include('/payment-tax/_list_payment_methods', ['payment_method_id' => Sisnanceiro\Models\PaymentMethod::TRANSFER])
                            </div>
                            <div id="bank-draft" class="tab-pane">
                                @include('/payment-tax/_list_payment_methods', ['payment_method_id' => Sisnanceiro\Models\PaymentMethod::BANK_DRAFT])
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</article>
@endsection

@section('scripts')
<script type="text/javascript">

    Main.dataTableOptions.serverSide = true;
    Main.dataTableOptions.ajax = {
            url: "payment-tax",
            type: 'POST'
    };
    Main.dataTableOptions.columns = [
        { data: 'name'},
        { data: 'bank_account_name'},
        { data: 'days_for_payment', 'searchable': false },
        { data: 'business_day', 'searchable': false },
        {
            bSortable: false,
            mRender: function(data, type, row) {
                var html = '<a href="payment_tax/update/'+row.id+'"><i class="fa fa-pencil"></i></a>'
                    html+= '<a href="payment_tax/delete/'+row.id+'" class="delete-record" data-title="Excluir esta taxa?" data-ask="Tem certeza que deseja excluir esta taxa '+ row.name +'?"><i class="fa fa-trash"></i></a>';
                return html;
            }
        }
    ];

    var dataTables2 = $('#dt_basic_2').DataTable({...Main.dataTableOptions, ajax: { url: 'payment-tax', type: 'POST', data: {payment_method_id: 2} } }); //CREDIT_CARD
    var dataTables1 = $('#dt_basic_1').DataTable({...Main.dataTableOptions, ajax: { url: 'payment-tax', type: 'POST', data: {payment_method_id: 1} } }); //DEBIT_CARD
    var dataTables5 = $('#dt_basic_5').DataTable({...Main.dataTableOptions, ajax: { url: 'payment-tax', type: 'POST', data: {payment_method_id: 5} } }); //ORDER
    var dataTables6 = $('#dt_basic_6').DataTable({...Main.dataTableOptions, ajax: { url: 'payment-tax', type: 'POST', data: {payment_method_id: 6} } }); //TRANSFER
    var dataTables7 = $('#dt_basic_4').DataTable({...Main.dataTableOptions, ajax: { url: 'payment-tax', type: 'POST', data: {payment_method_id: 7} } }); //BANK_DRAFT

</script>
@endsection
