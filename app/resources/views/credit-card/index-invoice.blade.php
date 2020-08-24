@extends('layouts.app')

@section('content')

<div class="d-flex w-100 home-header">
    <div>
        <h1 class="page-header"><i class="fa fa-credit-card"></i> {{ $title }} </h1>
    </div>
    <div class="ml-auto">
        <ul class="sa-sparks">
            <li class="sparks-info">
                <h5>
                    <small>Fatura</small>
                    <span class="text-red">
                        <i class="fa fa-money"></i>&nbsp;
                        <span id="total-to-pay" class="pull-right"></span>
                    </span>
                </h5>
            </li>
            <li class="sparks-info">
                <h5>
                    <small>Status</small>
                    <span class="text-green">
                        <i class="fa fa-check"></i>&nbsp;
                        <span id="status" class="pull-right">{{ $status }}</span>
                    </span>
                </h5>
            </li>
                <li class="sparks-info">
                <h5>
                    <small>Dia de fechamento</small>
                    <span class="text">
                        <i class="fa fa-calendar"></i>&nbsp;
                        <span id="closing-day" class="pull-right">{{ $endDateFormat }}</span>
                    </span>
                </h5>
            </li>
            </li>
                <li class="sparks-info">
                <h5>
                    <small>Data de vencimento</small>
                    <span class="text">
                        <i class="fa fa-calendar"></i>&nbsp;
                        <span id="payment-day" class="pull-right">{{ $dueDate }}</span>
                    </span>
                </h5>
            </li>
        </ul>
    </div>
</div>

<div id="content" style="opacity:1;">
    <div class="d-flex w-100">

        <section id="widget-grid" class="w-100">

            <div class="mb-10">
                <a
                    href="/credit-card/<?=$model->id;?>/create"
                    class="btn btn-sm btn-success open-modal"
                    target = "#remoteModal"
                    rel = "tooltip"
                    data-placement = "top"
                    title = "Adicionar nova transação"
                > <i class="fa fa-plus"></i> Incluir </a>
            </div>

            <div class="row">
                <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12 sortable-grid ui-sortable">
                    <div class="well" style="margin-bottom: 2px">
                        <div class="widget-body">

                            <input type="hidden" name="Filter[start_date]" value="{{ $startDate }}" id="filter-range-start-date" />
                            <input type="hidden" name="Filter[end_date]" value="{{ $endDate }}" id="filter-range-end-date" />
                            <input type="hidden" name="Filter[main_parent_category_id]" value="{{ \Sisnanceiro\Models\BankCategory::CATEGORY_TO_PAY }}" id="Filter_main_parent_category_id" />

                            <div class="row mb-10">

                                <div class="col-sm-3">
                                   <select name="Filter[credit_card_id]" class="form-control selectpicker" id="Filter_credit_card_id" title="Selecione o cartão">
                                        @if($creditCards)
                                            @foreach($creditCards as $creditCard)
                                                <option value="{{ $creditCard->id }}" {{ $model->id == $creditCard->id ? 'selected' : null }}>{{ $creditCard->name }}</option>
                                            @endforeach
                                        @endif
                                   </select>
                                </div>

                                <div class="drp-container col-sm-3">
                                    <a href="?start_date={{ $previousStartDate }}&end_date={{ $previousEndDate }}"><i class="fa fa-chevron-left"></i></a>
                                        {{ $currentDate }}
                                    <a href="?start_date={{ $nextStartDate }}&end_date={{ $nextEndDate }}"><i class="fa fa-chevron-right"></i></a>
                                </div>

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
                                <table id="dt_basic" class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th width="6%">Vencto</th>
                                            <th>Categoria</th>
                                            <th>Cartão</th>
                                            <th width="40%">Descrição</th>
                                            <th width="6%">Valor</th>
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
        'credit_card_id': <?=$model->id;?>,
        'status': '',
        'description': ''
    };

    updateTotal(filter);

    Main.dataTableOptions.sDom = '';
    Main.dataTableOptions.serverSide = true;
    Main.dataTableOptions.aaSorting = [
        [1, 'asc']
    ];
    Main.dataTableOptions.ajax = {
        url: "/credit-card/<?=$model->id;?>",
        type: 'POST',
        data: function(d) {
            d.extra_search = filter;
        }
    };
    Main.dataTableOptions.columns = [
        { data: 'due_date' },
        { data: 'category_name'},
        { data: 'credit_card_name' },
        {
            data: 'description',
            mRender: function(data, type, row) {
                if(row.total_invoices > 1) {
                    return row.description + " (" + row.parcel_number + "/" + row.total_invoices + ") ";
                }
                return row.description;
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
                var html = '<div class="text-right">';
                    html += '<a href="/credit-card/<?=$model->id;?>/update/' + row.id + '" rel="tooltip" data-placement="top" data-original-title="Alterar o lançamento" class="btn btn-xs btn-warning open-modal" target="#remoteModal"><i class="fa fa-pencil"></i></a> ';
                    html += '<a href="/credit-card/<?=$model->id;?>/delete/' + row.id + '" rel="tooltip" data-placement="top" data-original-title="Excluir o lançamento" class="btn btn-xs btn-danger delete-record" data-title="Excluir este lançamento?" data-ask="Tem certeza que deseja excluir este lançamento?"><i class="fa fa-times"></i></a> ';
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
            'main_parent_category_id': $('#Filter_main_parent_category_id').val(),
            'description': $('#Filter_description').val(),
            'credit_card_id': $('#Filter_credit_card_id').val(),
            'status': $('#Filter_status_id').val(),
        };
        dataTables.ajax.json(filter);
        dataTables.draw();

    });

    function updateTotal(filter) {
        var extraSearch = {extra_search: filter};
        $.post('/credit-card/<?=$model->id;?>/get-total', extraSearch, function(json) {
            $("#total-to-pay").html(json.mask.to_pay.substr(1));
            $("#status").html(json.status);
            $("#closing-day").html(json.closing_day);
            $("#payment-day").html(json.payment_day);
        }, 'json');
    }

</script>
@endsection
