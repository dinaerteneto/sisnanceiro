@extends('layouts.app')

@section('content')

@section('breadcrumbs')
    {{ Breadcrumbs::render('bank-account') }}
@endsection

<div class="d-flex w-100 home-header">
    <div>
        <h1 class="page-header"><i class="fa fa-bank"></i> Contas </h1>
    </div>
</div>

<div id="content" style="opacity:1;">
    <div class="d-flex w-100">
        <section id="widget-grid" class="w-100">
            <div class="mb-10">
                <a
                    href="{{ url('/bank-account/create') }}"
                    class="btn btn-sm btn-success open-modal"
                    target = "#remoteModal"
                    rel = "tooltip"
                    data-placement = "top"
                    title = "Adicionar nova conta"

                > <i class="fa fa-plus"></i> Incluir conta </a>
            </div>

            <div class="row">
                <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12 sortable-grid ui-sortable">
                    <div class="jarviswidget well jarviswidget-color-darken">
                        <div class="widget-body no-padding">

                            <div class="dataTables_wrapper dt-bootstrap4 no-footer">
                                <input type="hidden" id="dt_url" value="{{ url('/bank-account') }}" />
                                <table id="dt_basic" class="table table-striped table-bordered table-hover" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Nome</th>
                                            <th>Saldo inicial</th>
                                            <th>Saldo atual</th>
                                            <th width="7%">Ações</th>
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
<script type="text/javascript" src="{{ asset('assets/js/custom/BankAccount.js') }}"></script>
@endsection
