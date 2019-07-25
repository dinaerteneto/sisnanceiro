@extends('layouts.app')

@section('content')

<div class="d-flex w-100 home-header">
    <div>
        <h1 class="page-header"><i class="fa fa-list-ul"></i> Transações </h1>
    </div>
</div>

<div id="content" style="opacity:1;">
    <div class="d-flex w-100">
        
        <section id="widget-grid" class="w-100">

            <div class="row">
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            resds
                        </div>
                    </div>
                </div>   
                         
            </div>

            <div class="mb-10">
                <a 
                    href="/bank-transaction/create" 
                    class="btn btn-sm btn-success open-modal"
                    target = "#remoteModal"
                    rel = "tooltip"
                    data-placement = "top"
                    title = "Adicionar nova transação"    

                > <i class="fa fa-plus"></i> Incluir transação </a>
            </div>            

            <div class="row">
                <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12 sortable-grid ui-sortable">
                    <div class="jarviswidget well jarviswidget-color-darken">
                        <div class="widget-body no-padding">

                            <div class="dataTables_wrapper dt-bootstrap4 no-footer">
                                <table id="dt_basic" class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th width="3%"></th>
                                            <th width="10%">Vencto</th>
                                            <th>Descrição</th>
                                            <th>Categoria</th>
                                            <th>Conta</th>
                                            <th width="10%">Valor</th>
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
            { 
                data: 'description',
                mRender: function(data, type, row) {
                    if(row.total_invoices > 1) {
                        return row.description + " (" + row.parcel_number + "/" + row.total_invoices + ") ";
                    }
                    return row.description;
                } 
            },
            { data: 'category_name' },
            { data: 'account_name' },
            { 
                data: 'net_value' ,
                mRender: function(data, type, row) {
                    if(row.main_category_id == 2) {
                        return '<span class="text-red">R$ '+ row.net_value +'</span>';
                    } else {
                        return '<span style="color: #0000FF">R$ '+ row.net_value +'</span>';
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
                    html += '<a href="/bank-transaction/update/'+row . id+'" rel="tooltip" data-placement="top" data-original-title="Alterar lançamento" class="btn btn-xs btn-warning open-modal" target="#remoteModal"><i class="fa fa-pencil"></i></a> ';
                    html += '<a href="/bank-transaction/delete/' + row.id + '" rel="tooltip" data-placement="top" data-original-title="Excluir lançamento" class="delete-record btn btn-xs btn-danger" data-title="Excluir este lançamento?" data-ask="Tem certeza que deseja excluir este lançamento?"><i class="fa fa-times"></i></a> ';
                    html + '</div>';
                    return html;
                }
            }
        ];
        var dataTables = $('#dt_basic').DataTable(Main.dataTableOptions);

        $('#dt_basic').on('draw.dt', function() {
            $('[rel="tooltip"]').tooltip();
        });
    
</script>
@endsection