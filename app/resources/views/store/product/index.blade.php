@extends('layouts.app')

@section('content')

<div class="d-flex w-100 home-header">
    <div>
        <h1 class="page-header"><i class="fa fa-fw fa-tags "></i> Produtos <span>&gt; Lista</span></h1>
    </div>
</div>

<div class="d-flex w-100">
    <section id="widget-grid" class="w-100">
        <div class="row">
            <article class="col-12 sortable-grid ui-sortable">

                    <div class="jarviswidget jarviswidget-color-blue-dark" id="wid-id-0" role="widget">

                        <header>
                            <div class="widget-header">	
                                <span class="widget-icon"> <i class="fa fa-table"></i> </span>
                                <h2>Produtos</h2>
                            </div>
                            <span class="ml-auto" role="menu"></span>

                            <div class="widget-toolbar">
                                <!-- add: non-hidden - to disable auto hide -->
                            </div>
                            <div class="jarviswidget-ctrls" role="menu">
                                <a href="/store/product/create" class="button-icon " rel="tooltip" title="" data-placement="bottom" data-original-title="Incluir novo produto" style=""><i class="fa fa-pencil-square-o"></i></a>                                    
                            </div>                            
                        </header>

                        <!-- widget edit box -->
                        <div class="jarviswidget-editbox">
                            <!-- This area used as dropdown edit box -->
                        </div>
                        <!-- end widget edit box -->
    
                        <!-- widget content -->
                        <div class="widget-body p-0">
                            <table id="dt_basic" class="table table-striped table-bordered table-hover" width="100%">
                                <thead>			                
                                    <tr>
                                        <th>Cód</th>
                                        <th>Nome</th>
                                        <th>Categoria</th>
                                        <th>Marca</th>
                                        <th>Valor</th>
                                        <th>Estoque</th>
                                        <th width="5%">Ações</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
            </article>
        </div>
    </section>
</div>

@endsection

@section('scripts')
<script type="text/javascript">
    Main.dataTableOptions.serverSide = true;
    Main.dataTableOptions.ajax = {
            url: "/store/product",
            type: 'POST'
    };
    Main.dataTableOptions.columns = [
        { data: 'sku'},
        { data: 'name'},
        { data: 'category_name'},
        { data: 'brand_name' },
        { data: 'price' },
        { data: 'total_in_stock' },
        {
            bSortable: false,
            mRender: function(data, type, row) {
                var id = row.store_product_id !== null ? row.store_product_id : row.id;
                return '<a href="/store/product/update/'+id+'"><i class="fa fa-pencil"></i></a> <a href="/store/product/delete/'+row.id+'"><i class="fa fa-trash"></i></a>';
            }
        }
    ];

    var dataTables = $('#dt_basic').DataTable(Main.dataTableOptions);
</script>
@endsection