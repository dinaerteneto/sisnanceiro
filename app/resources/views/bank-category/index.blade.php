@extends('layouts.app')

@section('content')

<div class="d-flex w-100 home-header">
    <div>
        <h1 class="page-header"><i class="fa fa-table fa-fw "></i> Table <span>&gt; Normal Tables</span></h1>
    </div>
    <div class="ml-auto">
        <ul class="sa-sparks">
            <li class="sparks-info">
                <h5> <small>My Income</small> <span class="text-blue">$47,171</span></h5>
                <div class="sparkline text-blue d-none d-xl-block"><canvas width="89" height="26" style="display: inline-block; width: 89px; height: 26px; vertical-align: top;"></canvas></div>
            </li>
            <li class="sparks-info">
                <h5> <small>Site Traffic</small> <span class="text-purple"><i class="fa fa-arrow-circle-up" data-rel="bootstrap-tooltip" title="Increased"></i>&nbsp;45%</span></h5>
                <div class="sparkline text-purple d-none d-xl-block"><canvas width="82" height="26" style="display: inline-block; width: 82px; height: 26px; vertical-align: top;"></canvas></div>
            </li>
            <li class="sparks-info">
                <h5> <small>Site Orders</small> <span class="text-green-dark"><i class="fa fa-shopping-cart"></i>&nbsp;2447</span></h5>
                <div class="sparkline text-green-dark d-none d-xl-block"><canvas width="82" height="26" style="display: inline-block; width: 82px; height: 26px; vertical-align: top;"></canvas></div>
            </li>
        </ul>
    </div>
</div>

<article class="col-12 sortable-grid">
    <div class="jarviswidget jarviswidget-style-2 no-padding">
        <header role="heading" class="">
            <div class="widget-header">
                <span class="widget-icon"> <i class="fa fa-calendar"></i> </span>
                <h2>Categorias</h2>
            </div>
            <span class="ml-auto" role="menu"></span>
            <span class="jarviswidget-loader" role="menu"><i class="fa fa-refresh fa-spin"></i></span>            
            <ul id="myTab" class="nav nav-tabs ml-auto in">
                <li class="nav-item">
                    <a href="#s1" data-toggle="tab" aria-expanded="false" class="nav-link">Receitas</a>
                </li>
                <li class="nav-item">
                    <a href="#s2" data-toggle="tab" aria-expanded="false" class="nav-link">Despesas</a>
                </li>
            </ul>
        </header>

        <div role="content">

            <div id="myTabContent" class="tab-content padding-10">
                <div class="tab-pane" id="s1">
                    Receitas
                    @if ($categoriesReceive)
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($categoriesReceive as $toReceive)
                            <tr>
                                <td>
                                    <div>{{ $toReceive['text'] }}</div>
                                </td>
                                <td style="text-align: right">
                                    <a 
                                        href="/bank-category/create?main_parent_category_id={{$toReceive['main_parent_category_id']}}&parent_category_id={{$toReceive['parent_category_id']}}"
                                        class = "btn btn-success open-modal"
                                        target = "#remoteModal"
                                        rel = "tooltip"
                                        data-placement = "top"
                                        title = "Adicionar nova subcategoria"                                       
                                    >
                                        <i class="fa fa-plus"></i>
                                    </a>
                                    <a 
                                        href="/bank-category/update?id={{$toReceive['id']}}"
                                        class = "btn btn-info open-modal"
                                        target = "#remoteModal"
                                        rel = "tooltip"
                                        data-placement = "top"
                                        title = "Alterar categoria"                                       
                                    >
                                        <i class="fa fa-pencil"></i>
                                    </a>
                                    <a 
                                        href="/bank-category/delete?id={{$toReceive['id']}}"
                                        class = "btn btn-danger delete-record"
                                        target = "#remoteModal"
                                        rel = "tooltip"
                                        data-placement = "top"
                                        title = "Excluir categoria",
                                        data-title="Excluir categoria",
                                        data-ask="Tem certeza que deseja excluir esta categoria?"
                                    >
                                        <i class="fa fa-trash-o"></i>
                                    </a>
                                </td>
                            </tr>
                            @if (isset($toReceive['children']))
                            @foreach ($toReceive['children'] as $child)
                            <tr>
                                <td>
                                    <div style="padding-left: 20px">{{ $child['text'] }}</div>
                                </td>
                                <td style="text-align: right">
                                    <a 
                                        href="/bank-category/update?id={{$child['id']}}"
                                        class = "btn btn-info open-modal"
                                        target = "#remoteModal"
                                        rel = "tooltip"
                                        data-placement = "top"
                                        title = "Alterar categoria"                                       
                                    >
                                        <i class="fa fa-pencil"></i>
                                    </a>
                                    <a 
                                        href="/bank-category/delete?id={{$child['id']}}"
                                        class = "btn btn-danger delete-record"
                                        target = "#remoteModal"
                                        rel = "tooltip"
                                        data-placement = "top"
                                        title = "Excluir categoria",
                                        data-title="Excluir categoria",
                                        data-ask="Tem certeza que deseja excluir esta categoria?"
                                    >
                                        <i class="fa fa-trash-o"></i>
                                    </a>                                    
                                </td>
                            </tr>
                            @endforeach
                            @endif
                            @endforeach
                        </tbody>
                    </table>
                    @endif
                </div>

                <div class="tab-pane" id="s2">
                    Despesas
                    @if ($categoriesPay)
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($categoriesPay as $toPay)
                            <tr>
                                <td>
                                    <div>{{ $toPay['text'] }}</div>
                                </td>
                                <td style="text-align: right">
                                    <a 
                                        href="/bank-category/create?main_parent_category_id={{$toPay['main_parent_category_id']}}&parent_category_id={{$toPay['parent_category_id']}}"
                                        class = "btn btn-success open-modal"
                                        target = "#remoteModal"
                                        rel = "tooltip"
                                        data-placement = "top"
                                        title = "Adicionar nova subcategoria"                                       
                                    >
                                        <i class="fa fa-plus"></i>
                                    </a>
                                    <a 
                                        href="/bank-category/update?id={{$toPay['id']}}"
                                        class = "btn btn-info open-modal"
                                        target = "#remoteModal"
                                        rel = "tooltip"
                                        data-placement = "top"
                                        title = "Alterar categoria"                                       
                                    >
                                        <i class="fa fa-pencil"></i>
                                    </a>
                                    <a 
                                        href="/bank-category/delete?id={{$toPay['id']}}"
                                        class = "btn btn-danger delete-record"
                                        target = "#remoteModal"
                                        rel = "tooltip"
                                        data-placement = "top"
                                        title = "Excluir categoria",
                                        data-title="Excluir categoria",
                                        data-ask="Tem certeza que deseja excluir esta categoria?"
                                    >
                                        <i class="fa fa-trash-o"></i>
                                    </a>
                                </td>
                            </tr>
                            @if (isset($toPay['children']))
                            @foreach ($toPay['children'] as $child)
                            <tr>
                                <td>
                                    <div style="padding-left: 20px">{{ $child['text'] }}</div>
                                </td>
                                <td style="text-align: right">
                                    <a 
                                        href="/bank-category/update?id={{$child['id']}}"
                                        class = "btn btn-info open-modal"
                                        target = "#remoteModal"
                                        rel = "tooltip"
                                        data-placement = "top"
                                        title = "Alterar categoria"                                       
                                    >
                                        <i class="fa fa-pencil"></i>
                                    </a>
                                    <a 
                                        href="/bank-category/delete?id={{$child['id']}}"
                                        class = "btn btn-danger delete-record"
                                        target = "#remoteModal"
                                        rel = "tooltip"
                                        data-placement = "top"
                                        title = "Excluir categoria",
                                        data-title="Excluir categoria",
                                        data-ask="Tem certeza que deseja excluir esta categoria?"
                                    >
                                        <i class="fa fa-trash-o"></i>
                                    </a>                                    
                                </td>
                            </tr>
                            @endforeach
                            @endif
                            @endforeach
                        </tbody>
                    </table>
                    @endif
                </div>

            </div>
        </div>
</article>

@endsection