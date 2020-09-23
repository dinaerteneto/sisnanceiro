@extends('layouts.app')

@section('content')

<div class="d-flex w-100 home-header">
    <div>
        <h1 class="page-header"><i class="fa fa-credit-card"></i> Cartões de crédito </h1>
    </div>
    <!--
    <div class="ml-auto">
        <ul class="sa-sparks">
            <li class="sparks-info">
                <h5>
                    <small>Valor total</small>
                    <span class="text-red">
                        <i class="fa fa-arrow-circle-down"></i>&nbsp;
                        <span id="total-to-pay" class="pull-right">0</span>
                    </span>
                </h5>
            </li>
            <li class="sparks-info">
                <h5>
                    <small>Limite disponível</small>
                    <span class="text-green">
                        <i class="fa fa-arrow-circle-up"></i>&nbsp;
                        <span id="total-to-receive" class="pull-right">0</span>
                    </span>
                </h5>
            </li>
            <li class="sparks-info">
                <h5>
                    <small>Total</small>
                    <span id="total" class="">
                    0
                    </span>
                </h5>
            </li>
        </ul>
    </div>
    -->
</div>

<div id="content" style="opacity:1;">
    <div class="d-flex w-100">

        <section id="widget-grid" class="w-100">

            <div class="mb-10">
                <a
                    href="/credit-card/create"
                    class="btn btn-sm btn-success open-modal"
                    target = "#remoteModal"
                    rel = "tooltip"
                    data-placement = "top"
                    title = "Adicionar novo cartão de crédito"
                > <i class="fa fa-plus"></i> Incluir </a>
            </div>

            <div class="row">
                <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12 sortable-grid ui-sortable">
                    <div class="well" style="margin-bottom: 2px">
                        <div class="widget-body">

                            <input type="hidden" name="Filter[start_date]" value="{{ Carbon\Carbon::now()->startOfMonth()->format('Y-m-d') }}" id="filter-range-start-date" />
                            <input type="hidden" name="Filter[end_date]" value="{{ Carbon\Carbon::now()->endOfMonth()->format('Y-m-d') }}" id="filter-range-end-date" />

                            <div class="row mb-10">

                            @if($data)
                                @foreach($data as $item)
                                <div class="col-sm-4">
                                    <div class="jarviswidget jarviswidget-color-blueDark"
                                        data-widget-colorbutton="false"
                                        data-widget-editbutton="false"
                                        data-widget-togglebutton="false"
                                        data-widget-deletebutton="false"
                                        data-widget-fullscreenbutton="false"
                                        data-widget-custombutton="false"
                                        data-widget-sortable="false">
                                        <header>
                                            <h2>{{ $item['brand_name'] }} - {{ $item['name'] }}</h2>
                                        </header>
                                        <div>
                                            <div class="widget-body">
                                                <h3>Fatura aberta</h3>

                                                <div class="row">
                                                    <div class="col-sm-6">Valor parcial</div>
                                                    <div class="col-sm-6 text-right">R$ {{ $item['maskPartialValue'] }}</div>
                                                </div>

                                                <div class="row mb-10">
                                                    <div class="col-sm-6">Fecha em</div>
                                                    <div class="col-sm-6 text-right">{{ $item['closesIn'] }}</div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        R$ {{ $item['maskPartialValue'] }} de R$ {{ $item['limit'] }}
                                                        <div class="progress right" rel="tooltip" data-original-title="{{ $item['maskTotalPercent'] }}%" data-placement="top">
                                                            <div class="progress-bar bg-color-teal" data-transitiongoal="{{ $item['maskTotalPercent'] }}" style="width: {{ $item['totalPercent'] < 10 ? 10 : ceil($item['totalPercent']) }}%;" aria-valuenow="{{ $item['maskTotalPercent'] }}">{{ $item['maskTotalPercent'] }}%</div>
                                                        </div>
                                                        Limite Disponível R$ {{ $item['availableLimit'] }}
                                                    </div>

                                                </div>
                                                <div class="widget-footer">
                                                    <a href="/credit-card/delete/{{ $item['id'] }}"
                                                        data-title="Excluir este cartão?"
                                                        data-ask="Tem certeza que deseja excluir este cartão?"
                                                        class="btn btn-danger delete-record">Excluir cartão</a>
                                                    <a href="/credit-card/{{ $item['id'] }}" class="btn btn-success">Ver despesas</a>
                                                    <a href="/credit-card/{{ $item['id'] }}/create" class="open-modal btn btn-primary" target="#remoteModal">Adicionar despesa</a>
                                                    <a href="/credit-card/update/{{ $item['id'] }}" class="open-modal btn btn-primary" target="#remoteModal">Alterar</a>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach

                            @endif
                        </div>
                    </div>
                </article>
            </div>

            <div class="row">

            </div>
        </section>
    </div>
</div>
@endsection
