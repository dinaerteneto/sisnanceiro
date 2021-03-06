@extends('layouts.app')

@section('content')

@section('breadcrumbs')
    {{ Breadcrumbs::render('transfer') }}
@endsection

<div class="d-flex w-100 home-header">
    <div>
        <h1 class="page-header"><i class="fa fa-list-ul"></i> TransferĂȘncias </h1>
    </div>
</div>

<div id="content" style="opacity:1;">
    <div class="d-flex w-100">

        <section id="widget-grid" class="w-100">

            <div class="mb-10">
                <a
                    href="/bank-transaction/transfer/create"
                    class="btn btn-sm btn-success open-modal"
                    target = "#remoteModal"
                    rel = "tooltip"
                    data-placement = "top"
                    title = "Adicionar nova transferĂȘncia"
                > <i class="fa fa-plus"></i> Incluir </a>
            </div>


            <div class="row">
                <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12 sortable-grid ui-sortable">
                    <div class="well" style="margin-bottom: 2px">
                        <div class="widget-body">

                            <input type="hidden" name="Filter[start_date]" value="{{ Carbon\Carbon::now()->startOfMonth()->format('Y-m-d') }}" id="filter-range-start-date" />
                            <input type="hidden" name="Filter[end_date]" value="{{ Carbon\Carbon::now()->endOfMonth()->format('Y-m-d') }}" id="filter-range-end-date" />

                            <div class="row">
                                <div class="drp-container col-sm-3 row">
                                    <label class="col-md-2 control-label">
                                        <a href="javascript:void(0)" class="change-date" id="previous-date"><i class="fa fa-chevron-left"></i></a>
                                    </label>
                                    <div id="filter-range" class="form-control col-md-8" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ced4da;">
                                        <i class="fa fa-calendar"></i>&nbsp;
                                        <span></span>
                                        <i class="fa fa-caret-down"></i>
                                    </div>
                                    <label class="col-md-2 control-label">
                                        <a href="javascript:void(0)" class="pull-right change-date" id="next-date"><i class="fa fa-chevron-right"></i></a>
                                    </label>
                                </div>

                                <div class="col-sm-9">
                                    <div class="pull-right">
                                        <button class="btn btn-success" id="btn-search">
                                            <i class="fa fa-search"></i> Pesquisar
                                        </button>
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

                            <div class="dataTables_wrapper dt-bootstrap4 no-footer">
                                <input id="dt_url" type="hidden" value="{{ url('/bank-transaction/transfer') }}" />
                                <table id="dt_basic" class="table table-striped table-bordered table-hover" width="100%">
                                    <thead>
                                        <tr>
                                            <th width="20%">Data da transferĂȘncia</th>
                                            <th width="20%">ObservaĂ§ĂŁo</th>
                                            <th width="20%">De</th>
                                            <th width="20%">Para</th>
                                            <th width="10%">Valor</th>
                                            <th width="10%">AĂ§Ă”es</th>
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
        'description': '',
        'bank_categories_ids': [6]
    };

    Main.dataTableOptions.sDom = '';
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
    Main.dataTableOptions.columns = [
        { data: 'due_date' },
        { data: 'description' },
        { data: 'account_name_source' },
        { data: 'account_name_target' },
        { data: 'net_value' },
        {
            bSortable: false,
            mRender: function(data, type, row) {
                var html = '<div class="text-right">';
                html += '<a href="/bank-transaction/transfer/update/' + row.bank_invoice_transaction_id + '" rel="tooltip" data-placement="top" data-original-title="Alterar a transferĂȘncia" class="btn btn-xs btn-warning open-modal" target="#remoteModal"><i class="fa fa-pencil"></i></a> ';
                html += '<a href="/bank-transaction/transfer/delete/' + row.id + '" rel="tooltip" data-placement="top" data-original-title="Excluir a transferĂȘncia" class="btn btn-xs btn-danger delete-record" data-title="Excluir esta transferĂȘncia?" data-ask="Tem certeza que deseja excluir esta transferĂȘncia?"><i class="fa fa-times"></i></a> ';
                html += '</div>';
                return html;
            }
        }
    ];

    var dataTables = $('#dt_basic').DataTable(Main.dataTableOptions);

    $('#dt_basic').on('draw.dt', function() {
        $('[rel="tooltip"]').tooltip();
    });

    $('#btn-search').click( function() {
        filter = {
            'start_date': $('#filter-range-start-date').val(),
            'end_date': $('#filter-range-end-date').val(),
            'description': $('#Filter_description').val(),
            'bank_categories_ids': [6]
        };
        dataTables.ajax.json(filter);
        dataTables.draw();
    });

</script>
@endsection
