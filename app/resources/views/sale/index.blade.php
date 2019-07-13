@extends('layouts.app')

@section('content')

<div class="d-flex w-100 home-header">
    <div>
        <h1 class="page-header"><i class="fa fa-shopping-cart"></i> Vendas </h1>
    </div>
</div>

<div id="content" style="opacity:1;">
    <div class="d-flex w-100">
        <section id="widget-grid" class="w-100">
            <div class="mb-10">
                <a href="/sale/create" class="btn btn-sm btn-success"> <i class="fa fa-plus"></i> Incluir venda </a>
            </div>            

            <div class="row">
                <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12 sortable-grid ui-sortable">
                    <div class="jarviswidget well jarviswidget-color-darken">
                        <div class="widget-body no-padding">

                            <div class="dataTables_wrapper dt-bootstrap4 no-footer">
                                <table id="dt_basic" class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>Cód</th>
                                            <th>Status</th>
                                            <th>Data</th>
                                            <th>Cliente</th>
                                            <th>Operador</th>
                                            <th>Valor</th>
                                            <th width="12%">Ações</th>
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
<script type="text/javascript">
    
    $(document).ready(function() {

        Main.dataTableOptions.serverSide = true;
        Main.dataTableOptions.aaSorting = [[ 0, 'desc' ]];
        Main.dataTableOptions.ajax = {
                url: "/sale",
                type: 'POST'
        };
        Main.dataTableOptions.columns = [
            { data: 'company_sale_code', name: 'sale.company_sale_code', searchable: true },
            { data: 'status', name: 'sale.status', searchable: true},
            { data: 'sale_date', name: 'sale.sale_date', searchable: false},
            { data: 'customer_firstname', name: 'customer.firstname', searchable: true },
            { data: 'user_created_firstname', name: 'user_created.firstname', searchable: true },
            { data: 'net_value', name: 'net_value',  searchable: false },
            { 
                bSortable: false,
                mRender: function(data, type, row) {
                    var html = '<a href="/sale/view/'+row.id+'" rel="tooltip" data-placement="top" data-original-title="Visualizar" class="btn btn-xs btn-primary open-modal" target="#remoteModal"><i class="fa fa-search"></i></a> ';
                        html+= '<a href="/sale/update/'+row.id+'" rel="tooltip" data-placement="top" data-original-title="Alterar status da venda" class="btn btn-xs btn-warning open-modal" target="#remoteModal"><i class="fa fa-pencil"></i></a> ';
                        html+= '<a href="/sale/cancel/'+row.id+'" rel="tooltip" data-placement="top" data-original-title="Cancelar venda" class="delete-record btn btn-xs btn-danger" data-title="Cancelar esta venda?" data-ask="Tem certeza que deseja cancelar a venda: '+ row.company_sale_code +'?"><i class="fa fa-times"></i></a> ';
                        html+= '<div class="btn-group">';
                        html+= '<button class="btn btn-xs btn-success dropdown-toggle" data-toggle="dropdown"></button> ';
                        html+= '<ul class="dropdown-menu">';
                            html+= '<li>';
                                html+= '<a class="dropdown-item" href="/sale/print/'+row.id+'" target="_blank"><i class="text-danger fa fa-file-pdf-o margin-right-5px"></i> Imprimir A4</a>';
                            html+= '</li>';
                            html+= '<li>';
                                html+= '<a class="dropdown-item" href="/sale/coupon/'+row.id+'" target="_blank"><i class="text-info fa fa-file-text-o margin-right-5px"></i> Imprimir cupom</a>';
                            html+= '</li>';
                            html+= '<li>';
                                html+= '<a class="dropdown-item" href="/sale/copy/'+row.id+'"><i class="text-info fa fa-copy margin-right-5px"></i> Copiar venda</a>';
                            html+= '</li>';
                        html+= '</ul>';           
                        html+= '</div>';   
                    return html;
                }
            }
        ];    

        var dataTables = $('#dt_basic').DataTable(Main.dataTableOptions);        

        $('#dt_basic').on('draw.dt', function () {
            $('[rel="tooltip"]').tooltip();
        });

    });

</script>
@endsection