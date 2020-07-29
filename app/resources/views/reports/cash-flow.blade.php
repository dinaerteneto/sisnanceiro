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
                        <div class="widget-body">

                            <input type="hidden" name="Filter[start_date]" value="{{ Carbon\Carbon::now()->startOfMonth()->format('Y-m-d') }}" id="filter-range-start-date" />
                            <input type="hidden" name="Filter[end_date]" value="{{ Carbon\Carbon::now()->endOfMonth()->format('Y-m-d') }}" id="filter-range-end-date" />

                            <div class="row mb-10">
                                <div class="col-sm-3">
                                   <select name="Filter[bank_account_id]" class="form-control selectpicker" id="Filter_bank_account_id" title="Todas as contas" multiple>
                                        @if($bankAccounts)
                                            @foreach($bankAccounts as $bankAccount)
                                                <option value="{{ $bankAccount->id }}">{{ $bankAccount->name }}</option>
                                            @endforeach
                                        @endif
                                   </select>
                                </div>

                                <div class="drp-container col-sm-3">
                                    <div id="filter-range" class="form-control" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ced4da; width: 100%">
                                        <i class="fa fa-calendar"></i>&nbsp;
                                        <span></span>
                                        <i class="fa fa-caret-down"></i>
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="pull-right">
                                        <button class="btn btn-success" id="btn-search">
                                            <i class="fa fa-search"></i> Pesquisar
                                        </button>

                                    </div>

                                </div>

                            </div>

                            <div class="dataTables_wrapper dt-bootstrap4 no-footer">
                                <table id="dt_basic" class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th width="3%">&nbsp;</th>
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

    var filter = {
        'start_date': $('#filter-range-start-date').val(),
        'end_date': $('#filter-range-end-date').val(),
        'bank_account_id': '',
    };

    Main.dataTableOptions.serverSide = true;
    Main.dataTableOptions.bPaginate = false;
    Main.dataTableOptions.searching = false;
    Main.dataTableOptions.ajax = {
        url: "/reports/cash-flow",
        type: 'POST',
        data: function(d) {
            d.extra_search = filter;
        }
    };
    Main.dataTableOptions.columns = [
        {
            className:      'details-control',
            orderable:      false,
            data:           null,
            defaultContent: ''
        },
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


    $('#dt_basic tbody').on('click', 'td.details-control', function () {
        var tr = $(this).closest('tr');
        var row = dataTables.row( tr );

        if ( row.child.isShown() ) {
            row.child.hide();
            tr.removeClass('shown');
        }
        else {
            row.child( format(row.data()) ).show();
            tr.addClass('shown');
        }
    } );

    $('#btn-search').click( function() {
        filter = {
            'start_date': $('#filter-range-start-date').val(),
            'end_date': $('#filter-range-end-date').val(),
            'bank_account_id': $('#Filter_bank_account_id').val()
        };
        console.log(filter);
        dataTables.ajax.json(filter);
        dataTables.draw();
    });

    function format ( rowData ) {
        var div = $('<div/>')
            .addClass( 'loading' )
            .text( 'Aguarde...' );

        $.ajax({
            url: '/reports/cash-flow/detail',
            data: {
                date: rowData.date_value,
                bank_account_id: $('#Filter_bank_account_id').val()
            },
            success: function ( html ) {
                div
                .html( html )
                .removeClass( 'loading' );
            }
        });
    return div;
}

</script>
@endsection
