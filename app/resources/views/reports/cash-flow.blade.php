@extends('layouts.app')

@section('content')

<div class="d-flex w-100 home-header">
    <div>
        <h1 class="page-header"><i class="fa fa-calculator"></i> Fluxo de caixa </h1>
    </div>
</div>

<div id="content" style="opacity:1;">
    <div class="d-flex w-100">
        <section id="widget-grid" class="w-100">




            <div class="row">
                <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12 sortable-grid ui-sortable">
                    <div class="jarviswidget well jarviswidget-color-darken">
                        <div class="widget-body no-padding">

                            <div class="dataTables_wrapper dt-bootstrap4 no-footer">
                                <table id="dt_basic" class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>Data</th>
                                            <th>Saldo inicial</th>
                                            <th>Saídas</th>
                                            <th>Entrada</th>
                                            <th>Saldo</th>
                                        </tr>
                                    </thead>
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
<script type="text/javascript">
    Main.dataTableOptions.serverSide = true;
    Main.dataTableOptions.bPaginate = false;
    Main.dataTableOptions.ajax = {
            url: "/reports/cash-flow",
            type: 'POST'
    };
    Main.dataTableOptions.columns = [
        { data: 'date', 'searchable': false },
        { data: 'initial_balance', 'searchable': false,
            mRender: function(data, type, row) {
                if(row.initial_balance_value < 0) {
                    return '<span class="text-red">'+ row.initial_balance +'</span>';
                } else {
                    return '<span style="color: #0000FF">'+ row.initial_balance +'</span>';
                }
            }
        },
        { data: 'debit', 'searchable': false,
            mRender: function(data, type, row) {
                if(row.debit_value < 0) {
                    return '<span class="text-red">'+ row.debit +'</span>';
                } else {
                    return '<span style="color: #0000FF">'+ row.debit +'</span>';
                }
            }
        },
        { data: 'credit', 'searchable': true,
            mRender: function(data, type, row) {
                if(row.credit_value < 0) {
                    return '<span class="text-red">'+ row.credit +'</span>';
                } else {
                    return '<span style="color: #0000FF">'+ row.credit +'</span>';
                }
            }
        },
        { data: 'balance', 'searchable': false,
            mRender: function(data, type, row) {
                if(row.balance_value < 0) {
                    return '<span class="text-red">'+ row.balance +'</span>';
                } else {
                    return '<span style="color: #0000FF">'+ row.balance +'</span>';
                }
            }
        }
    ];

    var dataTables = $('#dt_basic').DataTable(Main.dataTableOptions);

</script>
@endsection
