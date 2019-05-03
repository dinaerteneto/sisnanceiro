@extends('layouts.app')

@section('content')

<div class="d-flex w-100 home-header">
    <div>
        <h1 class="page-header"><i class="fa fa-table fa-fw "></i> Financeiro <span>&gt; Categorias</span></h1>
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
                    <a href="#s1" data-toggle="tab" aria-expanded="false" class="nav-link {{ ($mainParentCategoryId == 3) ? 'active' : null }}">Receitas</a>
                </li>
                <li class="nav-item">
                    <a href="#s2" data-toggle="tab" aria-expanded="false" class="nav-link {{ ($mainParentCategoryId == 2) ? 'active' : null }}">Despesas</a>
                </li>
            </ul>
        </header>

        <div role="content">
            
            <form id="form-delete">
                @csrf
            </form>

            <div id="myTabContent" class="tab-content padding-10">
                <div class="tab-pane {{ ($mainParentCategoryId == 3) ? 'active' : null }}" id="s1">
                    <div class="">
                        <div class="col-sm-6 pull-left"><h4>Categorias de receitas</h4></div>
                        <div class="col-sm-6 pull-right align-right">
                            <a 
                                href="/bank-category/create/3"
                                class = "open-modal"
                                target = "#remoteModal"
                                rel = "tooltip"
                                data-placement = "top"
                                title = "Adicionar nova categoria de receitas"                                       
                            >
                                <i class="fa fa-plus"></i> NOVA CATEGORIA
                            </a>
                    </div>
                    </div>

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
                                        href="/bank-category/create/{{$toReceive['main_parent_category_id']}}/{{$toReceive['id']}}"
                                        class = "btn btn-success open-modal"
                                        target = "#remoteModal"
                                        rel = "tooltip"
                                        data-placement = "top"
                                        title = "Adicionar nova subcategoria"                                       
                                    >
                                        <i class="fa fa-plus"></i>
                                    </a>
                                    <a 
                                        href="/bank-category/update/{{$toReceive['id']}}"
                                        class = "btn btn-info open-modal"
                                        target = "#remoteModal"
                                        rel = "tooltip"
                                        data-placement = "top"
                                        title = "Alterar categoria"                                       
                                    >
                                        <i class="fa fa-pencil"></i>
                                    </a>
                                    <a 
                                        href="/bank-category/delete/{{$toReceive['id']}}"
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
                                        href="/bank-category/update/{{$child['id']}}"
                                        class = "btn btn-info open-modal"
                                        target = "#remoteModal"
                                        rel = "tooltip"
                                        data-placement = "top"
                                        title = "Alterar categoria"                                       
                                    >
                                        <i class="fa fa-pencil"></i>
                                    </a>
                                    <a 
                                        href="/bank-category/delete/{{$child['id']}}"
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

                <div class="tab-pane {{ ($mainParentCategoryId == 2) ? 'active' : null }}" id="s2">
                   <div class="">
                        <div class="col-sm-6 pull-left"><h4>Categorias de despesas</h4></div>
                        <div class="col-sm-6 pull-right align-right">
                            <a 
                                href="/bank-category/create/2"
                                class = "open-modal"
                                target = "#remoteModal"
                                rel = "tooltip"
                                data-placement = "top"
                                title = "Adicionar nova categoria de despesas"                                       
                            >
                                <i class="fa fa-plus"></i> NOVA CATEGORIA
                            </a>
                    </div>
                    </div>
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
                                        href="/bank-category/create/{{$toPay['main_parent_category_id']}}/{{$toPay['id']}}"
                                        class = "btn btn-success open-modal"
                                        target = "#remoteModal"
                                        rel = "tooltip"
                                        data-placement = "top"
                                        title = "Adicionar nova subcategoria"                                       
                                    >
                                        <i class="fa fa-plus"></i>
                                    </a>
                                    <a 
                                        href="/bank-category/update/{{$toPay['id']}}"
                                        class = "btn btn-info open-modal"
                                        target = "#remoteModal"
                                        rel = "tooltip"
                                        data-placement = "top"
                                        title = "Alterar categoria"                                       
                                    >
                                        <i class="fa fa-pencil"></i>
                                    </a>
                                    <a 
                                        href="/bank-category/delete/{{$toPay['id']}}"
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
                                        href="/bank-category/update/{{$child['id']}}"
                                        class = "btn btn-info open-modal"
                                        target = "#remoteModal"
                                        rel = "tooltip"
                                        data-placement = "top"
                                        title = "Alterar categoria"                                       
                                    >
                                        <i class="fa fa-pencil"></i>
                                    </a>
                                    <a 
                                        href="/bank-category/delete/{{$child['id']}}"
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