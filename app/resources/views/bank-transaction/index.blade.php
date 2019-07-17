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
                                            <th>Situação</th>
                                            <th>Data</th>
                                            <th>Descrição</th>
                                            <th>Categoria</th>
                                            <th>Conta</th>
                                            <th>Valor</th>
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
<script type="text/javascript" src="{{ asset('assets/js/custom/BankTransaction.js') }}"></script>
@endsection