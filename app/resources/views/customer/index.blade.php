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
                                <table class="table table-striped table-bordered table-hover">
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
                                    <tbody>
                                        <tr>
                                            <td>0001</td>
                                            <td>Cliente 1</td>
                                            <td>Pessoa física</td>
                                            <td>11 2019-7195</td>
                                            <td>11 96556-8653</td>
                                            <td>dinaerteneto@gmail.com</td>
                                            <td class="">
                                                <a href="/store/product/update/65"><i class="fa fa-pencil"></i></a> <a href="/store/product/delete/65"><i class="fa fa-trash"></i></a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>0001</td>
                                            <td>Cliente 1</td>
                                            <td>Pessoa física</td>
                                            <td>11 2019-7195</td>
                                            <td>11 96556-8653</td>
                                            <td>dinaerteneto@gmail.com</td>
                                            <td>
                                                <a href="/store/product/update/65"><i class="fa fa-pencil"></i></a> <a href="/store/product/delete/65"><i class="fa fa-trash"></i></a>
                                            </td>
                                        </tr>
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

@endsection