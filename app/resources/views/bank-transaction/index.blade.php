@extends('layouts.app')

@section('content')

@section('breadcrumbs')
    @if ($mainCategoryId)
        @if($mainCategoryId === \Sisnanceiro\Models\BankCategory::CATEGORY_TO_PAY)
            {{ Breadcrumbs::render('bank-transaction-pay') }}
        @else($mainCategoryId === \Sisnanceiro\Models\BankCategory::CATEGORY_TO_RECEIVE)
            {{ Breadcrumbs::render('bank-transaction-receive') }}
        @endif
    @else
        {{ Breadcrumbs::render('bank-transaction') }}
    @endif


@endsection

<div class="d-flex w-100 home-header">
    <div>
        <h1 class="page-header"><i class="fa fa-list-ul"></i> {{ $title }} </h1>
    </div>
    <div class="ml-auto">
        <ul class="sa-sparks">
            <li class="sparks-info">
                <h5>
                    <small>Despesa</small>
                    <span class="text-red">
                        <i class="fa fa-arrow-circle-down"></i>&nbsp;
                        <span id="total-to-pay" class="pull-right">0</span>
                    </span>
                </h5>
            </li>
            <li class="sparks-info">
                <h5>
                    <small>Receita</small>
                    <span class="text-green">
                        <i class="fa fa-arrow-circle-up"></i>&nbsp;
                        <span id="total-to-receive" class="pull-right">0</span>
                    </span>
                </h5>
            </li>
            <li class="sparks-info">
                <h5>
                    <small>Total</small>
                    <span id="total" class="">
                    </span>
                </h5>
            </li>
        </ul>
    </div>
</div>

<div id="content" style="opacity:1;">
    <div class="d-flex w-100">

        <section id="widget-grid" class="w-100">

            @if(!empty($urlMain))
            <div class="mb-10">
                <a
                    href="{!! $urlMain !!}/create"
                    class="btn btn-sm btn-success open-modal"
                    target = "#remoteModal"
                    rel = "tooltip"
                    data-placement = "top"
                    title = "Adicionar nova transação"
                > <i class="fa fa-plus"></i> Incluir </a>
            </div>
            @endif

            <div class="row">
                <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12 sortable-grid ui-sortable">
                    <div class="well" style="margin-bottom: 2px">
                        <div class="widget-body">

                            <input type="hidden" name="Filter[start_date]" value="{{ Carbon\Carbon::now()->startOfMonth()->format('Y-m-d') }}" id="filter-range-start-date" />
                            <input type="hidden" name="Filter[end_date]" value="{{ Carbon\Carbon::now()->endOfMonth()->format('Y-m-d') }}" id="filter-range-end-date" />
                            <input type="hidden" name="Filter[main_parent_category_id]" value="{{ $mainCategoryId }}" id="Filter_main_parent_category_id" />

                            <div class="row mb-10">

                                <div class="col-sm-3">
                                   <select name="Filter[bank_account_id]" class="form-control selectpicker" id="Filter_bank_account_id" title="Selecione as Conta" multiple>
                                        @if($bankAccounts)
                                            @foreach($bankAccounts as $bankAccount)
                                                <option value="{{ $bankAccount->id }}">{{ $bankAccount->name }}</option>
                                            @endforeach
                                        @endif
                                   </select>
                                </div>

                                <div class="col-sm-3">
                                   <select name="Filter[status_id]" class="form-control selectpicker multiple" title="Selecione os Estatos" id="Filter_status_id" multiple>
                                       <option value="1">Pendente</option>
                                       <option value="2">Vencida</option>
                                       <option value="3">Paga</option>
                                       <option value="4">Cancelada</option>
                                   </select>
                                </div>

                                <div class="drp-container col-sm-3 row">
                                    <label class="col-md-2 control-label">
                                        <a href="javascript:void(0)" class="pull-right change-date" id="previous-date"><i class="fa fa-chevron-left"></i></a>
                                    </label>
                                    <div id="filter-range" class="form-control col-md-8" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ced4da;">
                                        <i class="fa fa-calendar"></i>&nbsp;
                                        <span></span>
                                        <i class="fa fa-caret-down"></i>
                                    </div>
                                    <label class="col-md-2 control-label">
                                        <a href="javascript:void(0)" class="change-date" id="next-date"><i class="fa fa-chevron-right"></i></a>
                                    </label>
                                </div>

                                @if($mainCategoryId === \Sisnanceiro\Models\BankCategory::CATEGORY_TO_PAY)

                                <div class="col-sm-3">
                                    <label class="vcheck m-0">
                                        <input type="checkbox" class="checkbox style-0" name="Filter[group_by]" id="Filter_group_by" value="credit_card_id">
                                        <span>Agrupar por cartão</span>
                                    </label>
                                </div>

                                @endif

                            </div>

                            <div class="row">
                                <div class="col-sm-9">
                                    <div class="icon-addon addon-md">
                                        <input type="text" name="Filter[description]" id="Filter_description" class="form-control" />
                                        <label for="email" class="fa fa-search" rel="tooltip" title="" data-original-title="email"></label>
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="pull-right">
                                        <button class="btn btn-success" id="btn-search">
                                            <i class="fa fa-search"></i> Pesquisar
                                        </button>
                                        <!--
                                        <button class="btn btn-primary">
                                            <i class="fa fa-file-text"></i> Exportar
                                        </button>
                                        -->
                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>
                </article>
            </div>

            <div class="row">
                <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12 sortable-grid ui-sortable">
                    <div class="jarviswidget-color-darken">
                        <div class="widget-body no-padding">
                            <input type="hidden" id="dt_url" value="{{ url('/bank-transaction') }}" />
                            <div class="dataTables_wrapper dt-bootstrap4 no-footer">
                                <table id="dt_basic" class="table table-striped table-bordered table-hover" width="100%">
                                    <thead>
                                        <tr>
                                            <th width="2%"></th>
                                            <th width="6%">Vencto</th>
                                            <th>Conta</th>
                                            <th>Categoria</th>
                                            @if ($mainCategoryId == Sisnanceiro\Models\BankCategory::CATEGORY_TO_PAY)
                                                <th>Fornecedor</th>
                                            @elseif ($mainCategoryId == Sisnanceiro\Models\BankCategory::CATEGORY_TO_RECEIVE)
                                                <th>Cliente</th>
                                            @else
                                                <th>Cliente / Fornecedor</th>
                                            @endif
                                            <th width="">Descrição</th>
                                            <th width="">Valor</th>
                                            <th width="10%">Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </article>
            </div>
        </section>
    </div>
</div>
@endsection

@section('scripts')
<script type="text/javascript" src="{{ asset('assets/js/custom/BankTransaction.js') }}"></script>
<script type="text/javascript">

    var filter = {
        'start_date': $('#filter-range-start-date').val(),
        'end_date': $('#filter-range-end-date').val(),
        'main_parent_category_id': $('#Filter_main_parent_category_id').val(),
        'bank_account_id': '',
        'status': '',
        'description': '',
        'group_by': ''
    };

    updateTotal(filter);

//    Main.dataTableOptions.sDom = "<'dt-toolbar d-flex'<f><'ml-auto hidden-xs show-control'l>r>t<'dt-toolbar-footer d-flex'<'hidden-xs'i><'ml-auto'p>>";
    Main.dataTableOptions.bFilter = false;
    Main.dataTableOptions.paging = false;
    Main.dataTableOptions.serverSide = true;
    Main.dataTableOptions.aaSorting = [
        [1, 'asc']
    ];
    Main.dataTableOptions.ajax = {
        url: $('#dt_url').val(),
        type: 'POST',
        data: function(d) {
            d.extra_search = filter;
        }
    };
    Main.dataTableOptions.columns = [{
            // data: 'status',
            // name: 'status',
            bSortable: false,
            mRender: function(data, type, row) {
                return '<a href="javascript:void(0)" rel="tooltip" data-placement="top" data-original-title="'+ row.label_legend +'"><i class="fa fa-circle" style="color: ' + row.label_status + '"></i></a>';
            }
        },
        { data: 'due_date' },
        { data: 'account_name' },
        { data: 'category_name' },
        {
            data: 'name',
            mRender: function(data, type, row) {
               let icon = '';
                if(row.credit_card_id !== null && row.credit_card_id !== "") {
                    icon = '<i class="fa fa-credit-card"></i> ';
                }
                if(row.name !== null && row.name !== "") {
                    return `${icon} ${row.name}`;
                }
                return '';
            }
        },
        {
            data: 'description',
            mRender: function(data, type, row) {
                let icon = '';
                if(row.credit_card_id !== null && row.credit_card_id !== "") {
                    icon = '<i class="fa fa-credit-card"></i> ';
                }
                if(row.total_invoices > 1) {
                    return  `${icon}${row.description} (${row.parcel_number}/${row.total_invoices})`;
                }
                if(row.description !== null && row.description !== "") {
                    return `${icon} ${row.description}`;
                }
                return '';
            }
        },
        {
            data: 'net_value' ,
            mRender: function(data, type, row) {
                if(row.net_value_original < 0) {
                    return '<span class="text-red">'+ row.net_value +'</span>';
                } else {
                    return '<span class="text-blue">'+ row.net_value +'</span>';
                }
            }
        },
        {
            bSortable: false,
            mRender: function(data, type, row) {
                if (row.bank_category_id == 1 || row.credit_card_id != null) {
                    var html = '';
                } else {
                    var html = '<div class="text-right">';
                    if(row.status != 3) {
                        if(row.is_credit_card_invoice != null && row.is_credit_card_invoice != 0) {
                            html += `<a href="/bank-transaction/partial-pay/${row.id}"
                                rel="tooltip"
                                data-placement="top"
                                data-original-title="Pagar parcialmente"
                                class="btn btn-xs btn-primary open-modal"
                                target="#remoteModal">
                                    <i class="fa fa-check"></i>
                                </a> `;
                        }
                        var label = 'Pagar';
                        if(row.main_category_id == 3) {
                            label = 'Receber';
                        }
                        html += `<a href="/bank-transaction/set-paid/${row.id}"
                            rel="tooltip"
                            data-placement="top"
                            data-original-title="${label}"
                            class="btn btn-xs btn-success set-paid">
                                <i class="fa fa-check"></i>
                            </a> `;
                    }
                    if(row.bank_category_id == 6 || row.bank_category_id == 7) {
                        html += `<a href="/bank-transaction/transfer/update/${row.bank_invoice_transaction_id}"
                            rel="tooltip"
                            data-placement="top"
                            data-original-title="Alterar a transferência"
                            class="btn btn-xs btn-warning open-modal"
                            target="#remoteModal">
                                <i class="fa fa-pencil"></i>
                            </a> `;
                        html += `<a href="/bank-transaction/transfer/delete/${row.id}"
                            rel="tooltip"
                            data-placement="top"
                            data-original-title="Excluir a transferência"
                            class="btn btn-xs btn-danger delete-record"
                            data-title="Excluir esta transferência?"
                            data-ask="Tem certeza que deseja excluir esta transferência?">
                                <i class="fa fa-times"></i>
                            </a> `;
                    } else {
                        html += `<a href="/bank-transaction/${row.url_main}/update/${row.id}"
                            rel="tooltip"
                            data-placement="top"
                            data-original-title="Alterar lançamento"
                            class="btn btn-xs btn-warning open-modal"
                            target="#remoteModal">
                                <i class="fa fa-pencil"></i>
                            </a> `;
                        html += `<a href="/bank-transaction/delete/${row.id}"
                            rel="tooltip" data-placement="top"
                            data-original-title="Excluir lançamento"
                            class="btn btn-xs btn-danger open-modal"
                            target="#remoteModal"
                            data-title="Excluir este lançamento?"
                            data-ask="Tem certeza que deseja excluir este lançamento?">
                                <i class="fa fa-times"></i>
                            </a> `;
                    }
                }
                html += '</div>';
                return html;
            }
        }
    ];

    var dataTables = $('#dt_basic').DataTable(Main.dataTableOptions);

    $('body').on('click', '[rel=tooltip]', function () {
        $('[rel=tooltip]').tooltip("hide");
    });

    $('#dt_basic').on('draw.dt', function() {
        $('[rel="tooltip"]').tooltip();
    });

    $('#btn-search').click( function() {
        filter = {
            'start_date': $('#filter-range-start-date').val(),
            'end_date': $('#filter-range-end-date').val(),
            'main_parent_category_id': $('#Filter_main_parent_category_id').val(),
            'description': $('#Filter_description').val(),
            'bank_account_id': $('#Filter_bank_account_id').val(),
            'status': $('#Filter_status_id').val(),
            'group_by': $('#Filter_group_by').is(':checked') ? $('#Filter_group_by').val() : ''
        };
        dataTables.ajax.json(filter);
        dataTables.draw();

        updateTotal(filter);
    });


    function updateTotal(filter) {
        var extraSearch = {extra_search: filter};
        $.post('<?=url('/bank-transaction/get-total-by-main-category');?>', extraSearch, function(json) {
            $("#total-to-receive").html(json.mask.to_receive);
            $("#total-to-pay").html(json.mask.to_pay);
            if(json.total < 0) {
                $('#total').addClass('text-red');
            } else {
                $('#total').addClass('text-green');
            }
            if(json.current_balance < 0) {
                $('#current-balance').addClass('text-red');
            } else {
                $('#current-balance').addClass('text-green');
            }
            $("#total").html('<i class="fa fa-money"></i><span class="pull-right">&nbsp;'+ json.mask.total +'</span>');
        }, 'json');
    }




    /*
    $(document).ready(function() {
        $('.selectpicker').selectepicker();
    });
    */

</script>
@endsection
