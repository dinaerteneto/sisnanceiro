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
                                            <th width="5%">Ações</th>
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
    Main.dataTableOptions.serverSide = true;
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
                var html = '<a href="/sale/update/'+row.id+'"><i class="fa fa-pencil"></i></a>'
                    html+= '<a href="/sale/delete/'+row.id+'" class="delete-record" data-title="Cancelar esta venda?" data-ask="Tem certeza que deseja cancelar a venda: '+ row.company_sale_code +'?"><i class="fa fa-trash"></i></a>';
                return html;
            }
        }
    ];    

    var dataTables = $('#dt_basic').DataTable(Main.dataTableOptions);

</script>
@endsection