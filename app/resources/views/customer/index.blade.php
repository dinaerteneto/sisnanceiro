@extends('layouts.app')

@section('content')

@section('breadcrumbs')
    {{ Breadcrumbs::render('customer') }}
@endsection

<div class="d-flex w-100 home-header">
    <div>
        <h1 class="page-header"><i class="fa fa-group"></i> Clientes </h1>
    </div>
</div>

<div id="content" style="opacity:1;">
    <div class="d-flex w-100">
        <section id="widget-grid" class="w-100">
            <div class="mb-10">
                <a href="{{ url('/customer/create') }}" class="btn btn-sm btn-success"> <i class="fa fa-plus"></i> Incluir cliente </a>
            </div>

            <div class="row">
                <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12 sortable-grid ui-sortable">
                    <div class="jarviswidget well jarviswidget-color-darken">
                        <div class="widget-body no-padding">

                            <div class="dataTables_wrapper dt-bootstrap4 no-footer">

                                <input type="hidden" id="dt_url" value="{{ url('/customer') }}" />
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
    Main.dataTableOptions.serverSide = true;
    Main.dataTableOptions.ajax = {
        url: $('#dt_url').val(),
        type: 'POST'
    };
    Main.dataTableOptions.columns = [
        { data: 'id', 'searchable': false },
        { data: 'firstname'},
        { data: 'physical','searchable': false},
        { data: 'phone', 'searchable': false },
        { data: 'cellphone', 'searchable': false },
        { data: 'email', 'searchable': false },
        {
            bSortable: false,
            mRender: function(data, type, row) {
                var html = '<a href="customer/update/'+row.id+'" class="btn btn-xs btn-warning"><i class="fa fa-pencil"></i></a> '
                    html+= '<a href="customer/delete/'+row.id+'" class="btn btn-xs btn-danger delete-record" data-title="Excluir este cliente?" data-ask="Tem certeza que deseja excluir o cliente: '+ row.firstname +'?"><i class="fa fa-times"></i></a>';
                return html;
            }
        }
    ];

    var dataTables = $('#dt_basic').DataTable(Main.dataTableOptions);

</script>
@endsection
