@extends('layouts.app')

@section('content')

<div class="d-flex w-100 home-header">
    <div>
        <h1 class="page-header"><i class="fa fa-group"></i> Clientes </h1>
    </div>
</div>

<div id="content" style="opacity:1;">
    <div class="d-flex w-100">
        <section id="widget-grid" class="w-100">
            <div class="row">
                <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12 sortable-grid ui-sortable">
                    <div class="jarviswidget well jarviswidget-color-darken">
                        <div class="widget-body no-padding">

                            <div class="widget-body-toolbar">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="input-group">
                                            <input class="form-control" type="text" placeholder="Nome do cliente">
                                            <div class="input-group-btn">
                                                <button class="btn btn-default" type="button">
                                                    <i class="fa fa-search"></i> Buscar
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-8 align-right">
                                        <div class="btn-group">
                                            <a href="/customer/create" class="btn btn-sm btn-success"> <i class="fa fa-plus"></i> Incluir cliente </a>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="dataTables_wrapper dt-bootstrap4 no-footer">
                                <table id="dt_basic" class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>Cód</th>
                                            <th>Nome</th>
                                            <th>Tipo</th>
                                            <th>Telefone</th>
                                            <th>Celular</th>
                                            <th>E-Mail</th>
                                            <th width="5%">Ações</th>
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
$('document').ready(function() {
     $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
      });

    $('#dt_basic').DataTable({
        processing: true,
        serverSide: true,
        autoWidth: true,
        responsive: true,
        "sDom": "<'dt-toolbar d-flex'<f><'ml-auto hidden-xs show-control'l>r>"+
            "t"+
            "<'dt-toolbar-footer d-flex'<'hidden-xs'i><'ml-auto'p>>",  
        "classes": {
            "sWrapper": "dataTables_wrapper dt-bootstrap4"
        },       
        "oLanguage": {
            "sSearch": '<span class="input-group-addon"><i class="fa fa-search"></i></span>',
            'sProcessing': '..::Carregando::..',
            'sLengthMenu': '_MENU_',
            'sZeroRecords': 'Nenhum registro encontrado.',
            'sInfo': 'Exibindo de _START_ até _END_ no total de _TOTAL_ Registros',
            'sInfoEmpty': 'Nenhum registro encontrado',
            'sInfoFiltered': '(filtrado de _MAX_  registros)',
            'sInfoPostFix': '',
            'sUrl': '',            
            'oPaginate': {
                'sFirst':    'Primeiro',
                'sPrevious': 'Anterior',
                'sNext':     'Próximo',
                'sLast':     'Último'
            }            
        },         
        ajax: {
            url: "/customer",
            type: 'POST'
        },
        columns: [
            { data: 'sku' },
            { 
                name: 'name',
                mRender: function(data, type, row) {
                    if(row.attributes == null) {
                       return row.name;
                    }
                    return row.name + " - " + row.attributes;
                } 
            },
            { data: 'category_name'},
            { data: 'brand_name' },
            { data: 'price' },
            { data: 'total_in_stock' },
            { 
                mRender: function(data, type, row) {
                    var id = row.store_product_id !== null ? row.store_product_id : row.id;
                    return '<a href="/store/product/update/'+id+'"><i class="fa fa-pencil"></i></a> <a href="/store/product/delete/'+row.id+'"><i class="fa fa-trash"></i></a>';
                }
            }
        ]
    });
});    
</script>
@endsection