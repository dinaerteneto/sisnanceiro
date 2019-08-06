<?php
use Sisnanceiro\Models\BankCategory;
?>

@extends('layouts.app')

@section('content')

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
                        <i class="fa fa-arrow-circle-down"></i>
                        R$ 47,171
                    </span>
                </h5>
            </li>
            <li class="sparks-info">
                <h5>
                    <small>Receita</small>
                    <span class="text-green">
                        <i class="fa fa-arrow-circle-up"></i>
                        R$ 47,171
                    </span>
                </h5>
            </li>
            <li class="sparks-info">
                <h5>
                    <small>Balanço mensal</small>
                    <span class="text-green">
                        <i class="fa fa-bank"></i>
                        R$ 47,171
                    </span>
                </h5>
            </li>
            <li class="sparks-info">
                <h5>
                    <small>Saldo atual</small>
                    <span class="text-green">
                        <i class="fa fa-money"></i>
                        R$ 47,171
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

                            <input type="hidden" value="2019-08-01" id="filter-range-start-date" />
                            <input type="hidden" value="2019-08-04" id="filter-range-end-date" />

                            <div class="row mb-10">

                                <div class="col-sm-3">
                                   <select name="" multiple="multiple" id="Filter_bank_account_id">
                                       <option value="1">Conta 1</option>
                                       <option value="2">Conta 2</option>
                                   </select> 
                                </div>

                                <div class="col-sm-3">
                                   <select name="" multiple="multiple" id="Filter_status_id">
                                       <option value="1">Pendente</option>
                                       <option value="2">Vencida</option>
                                       <option value="2">Paga</option>
                                   </select> 
                                </div>

                                <div class="drp-container col-sm-3">
                                    <div id="filter-range" class="form-control" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ced4da; width: 100%">
                                        <i class="fa fa-calendar"></i>&nbsp;
                                        <span></span>
                                        <i class="fa fa-caret-down"></i>
                                    </div>
                                </div>
                                
                                

                            </div>     
                            
                            <div class="row">
                                <div class="col-sm-9">
                                    <div class="icon-addon addon-md">
                                        <input type="text" name="search" class="form-control" />
                                        <label for="email" class="fa fa-search" rel="tooltip" title="" data-original-title="email"></label>                                         
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="pull-right">
                                        <button class="btn btn-success" type="submit">
                                            <i class="fa fa-search"></i> Pesquisar
                                        </button>

                                        <button class="btn btn-primary">
                                            <i class="fa fa-file-text"></i> Exportar
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
                                            <th width="3%"></th>
                                            <th width="6%">Vencto</th>
                                            <th>Conta</th>
                                            <th>Categoria</th>
                                            @if ($mainCategoryId == BankCategory::CATEGORY_TO_PAY)
                                                <th>Fornecedor</th>
                                            @elseif ($mainCategoryId == BankCategory::CATEGORY_TO_RECEIVE)
                                                <th>Cliente</th>
                                            @else
                                                <th>Cliente / Fornecedor</th>
                                            @endif
                                            <th width="30%">Descrição</th>
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
    
        Main.dataTableOptions.sDom = '';
        Main.dataTableOptions.serverSide = true;
        // Main.dataTableOptions.aaSorting = [
        //     [0, 'desc']
        // ];
        Main.dataTableOptions.ajax = {
            url: "/bank-transaction",
            type: 'POST'
        };
        Main.dataTableOptions.columns = [{
                // data: 'status',
                // name: 'status',
                bSortable: false,
                mRender: function(data, type, row) {
                    return '<i class="fa fa-circle" style="color: ' + row.label_status + '"></i>';
                }
            },
            { data: 'due_date' },
            { data: 'account_name' },
            { data: 'category_name' },
            { data: 'name'},
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
                    if(row.main_category_id == 2) {
                        return '<span class="text-red">'+ row.net_value +'</span>';
                    } else {
                        return '<span style="color: #0000FF">'+ row.net_value +'</span>';
                    }
                }
            },
            {
                bSortable: false,
                mRender: function(data, type, row) {
                    var html = '<div class="text-right">';
                    if(row.status != 3) {
                         html += '<a href="/bank-transaction/set-paid/' + row.id + '" rel="tooltip" data-placement="top" data-original-title="Pago" class="btn btn-xs btn-success set-paid"><i class="fa fa-check"></i></a> ';
                    }
                    html += '<a href="{!! $urlMain !!}/update/' + row.id + '" rel="tooltip" data-placement="top" data-original-title="Alterar lançamento" class="btn btn-xs btn-warning open-modal" target="#remoteModal"><i class="fa fa-pencil"></i></a> ';
                    html += '<a href="/bank-transaction/delete/' + row.id + '" rel="tooltip" data-placement="top" data-original-title="Excluir lançamento" class="btn btn-xs btn-danger open-modal" target="#remoteModal"><i class="fa fa-times"></i></a> ';
                    html + '</div>';
                    return html;
                }
            }
        ];

        var dataTables = $('#dt_basic').DataTable(Main.dataTableOptions);

        $('#dt_basic').on('draw.dt', function() {
            $('[rel="tooltip"]').tooltip();
        });

        $(document).ready(function() {
            $('#Filter_bank_account_id').multiselect({
                enableClickableOptGroups: true,
                includeSelectAllOption: true,
                nSelectedText: ' Várias selec..',
                allSelectedText: 'Contas',
                nonSelectedText: 'Todas as contas',
                selectAllText: 'Sel. todas',
                buttonWidth: '100%',
                buttonClass: 'multiselect dropdown-toggle btn btn-default text-left box-shadow-none'
            });        
            $('#Filter_status_id').multiselect({
                enableClickableOptGroups: true,
                includeSelectAllOption: true,
                nSelectedText: ' Várias selec..',
                allSelectedText: 'Estatos',
                nonSelectedText: 'Todos Estatos',
                selectAllText: 'Sel. todos',
                buttonWidth: '100%',
                buttonClass: 'multiselect dropdown-toggle btn btn-default text-left box-shadow-none'
            });        
        })
    
</script>
@endsection