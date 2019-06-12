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
            <div class="mb-10">
                <a href="/customer/create" class="btn btn-sm btn-success"> <i class="fa fa-plus"></i> Incluir cliente </a>
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
            { data: 'id', 'searchable': false },
            { data: 'firstname'},
            { data: 'physical','searchable': false},
            { data: 'phone', 'searchable': false },
            { data: 'cellphone', 'searchable': false },
            { data: 'email', 'searchable': false },
            { 
                mRender: function(data, type, row) {
                    var html = '<a href="/customer/update/'+row.id+'"><i class="fa fa-pencil"></i></a>'
                        html+= '<a href="/customer/delete/'+row.id+'" class="delete-record" data-title="Excluir este cliente?" data-ask="Tem certeza que deseja excluir o cliente: '+ row.firstname +'?"><i class="fa fa-trash"></i></a>';
                    return html;
                }
            }
        ]
    });
});    
</script>
@endsection