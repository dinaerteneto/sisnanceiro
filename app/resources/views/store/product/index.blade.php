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

                            <div class="widget-toolbar">
                                <!-- add: non-hidden - to disable auto hide -->
                            </div>
                        </header>

                        <!-- widget div-->
                        <div role="content">

                            <!-- widget content -->
                            <div class="widget-body">

					
                                <table id="dt_basic" class="table table-striped table-bordered table-hover" width="100%">
                                    <thead>			                
                                        <tr>
                                            <th data-hide="">Cód</th>
                                            <th data-class="">Nome</th>
                                            <th data-hide="">Categoria</th>
                                            <th data-hide="">Marca</th>
                                            <th data-hide="">Valor</th>
                                            <th data-hide="">Estoque</th>
                                            <th data-hide=""></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>0001</td>
                                            <td>Camiseta</td>
                                            <td>Roupa</td>
                                            <td>Brandli</td>
                                            <td>R$ 50,00</td>
                                            <td>5</td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>0001</td>
                                            <td>Camiseta</td>
                                            <td>Roupa</td>
                                            <td>Brandli</td>
                                            <td>R$ 50,00</td>
                                            <td>5</td>
                                            <td></td>
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

@endsection