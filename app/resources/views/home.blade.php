@extends('layouts.app')

@section('content')
<div class="d-flex w-100 home-header">
    <div>
        <h1 class="page-header"><i class="fa-fw fa fa-home"></i> Home <span>&gt; Dashboard</span></h1>
    </div>
</div>

<section>

    <div class="row">

        <article class="col-3 sortable-grid ui-sortable">
            <div class="jarviswidget well">
                <div class="widget-body">
                    <div class="col-sm-12">
                        <div class="pull-left">
                            <i class="fa fa-bank fa-4x"></i>&nbsp;
                        </div>
                        <div class="">
                            <div class="text-center">
                                <h3>
                                    <b>R$ {{ $aBallance['bank_account'] }}</b><br />
                                    Saldo atual
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </article>

        <article class="col-3 sortable-grid ui-sortable">
            <div class="jarviswidget well">
                <div class="widget-body">
                    <div class="col-sm-12">
                        <div class="pull-left">
                            <i class="fa fa-arrow-circle-up fa-4x text-green"></i>&nbsp;
                        </div>
                        <div class="">
                            <div class="text-center">
                                <h3>
                                    <b>R$ {{ $aBallance['to_receive'] }}</b><br />
                                    Contas a receber
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </article>

        <article class="col-3 sortable-grid ui-sortable">
            <div class="jarviswidget well">
                <div class="widget-body">
                    <div class="col-sm-12">
                        <div class="pull-left">
                            <i class="fa fa-arrow-circle-down fa-4x text-red"></i>&nbsp;
                        </div>
                        <div class="">
                            <div class="text-center">
                                <h3>
                                    <b>R$ {{ $aBallance['to_pay'] }}</b><br />
                                    Contas a pagar
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </article>

        <article class="col-3 sortable-grid ui-sortable">
            <div class="jarviswidget well">
                <div class="widget-body">
                    <div class="col-sm-12">
                        <div class="pull-left">
                            <i class="fa fa-credit-card fa-4x"></i>&nbsp;
                        </div>
                        <div class="">
                            <div class="text-center">
                                <h3>
                                    <b>R$ {{ $aBallance['credit_card'] }}</b><br />
                                    Cartão de crédito
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </article>
    </div>

    <div class="row">

        <div class="col-sm-12 text-center">
            <h6 style="margin-top: 5px">
                <a href="?iMonth=<?=$iPreviousMonth;?>">
                    <i class="fa fa-chevron-left"></i>
                </a>
                <span>{{ $currentDate }}</span>
                <a href="?iMonth=<?=$iNextMonth;?>">
                    <i class="fa fa-chevron-right"></i>
                </a>
            </h6>
        </div>

    </div>

    <div class="row">
        <article class="col-sm-6">
            <div class="jarviswidget jarviswidget-color-blueDark">
                <header class="ui-sortable-handle">
                    <div class="widget-header">
                        <span class="widget-icon"> <i class="fa fa-arrow-circle-down text-red"></i> </span>
                        <h2>Contas a pagar por categorias</h2>
                    </div>
                </header>
                <div class="widget-body" style="position: relative; height:40vh">
                    @if(!$jsonValue)
                        <p>Você ainda não possuí lançamentos</p>
                    @endif
                    <canvas id="chart-category-to-receive"></canvas>
                </div>
            </div>
        </article>

        <article class="col-sm-6">
            <div class="jarviswidget jarviswidget-color-blueDark">
                <header class="ui-sortable-handle">
                    <div class="widget-header">
                        <span class="widget-icon"> <i class="fa fa-arrow-circle-down text-red"></i> </span>
                        <h2>Contas a pagar por categoria de orçamento</h2>
                    </div>
                </header>
                <div class="widget-body" style="position: relative; height:40vh">
                    @if(!$jsonValue)
                        <p>Você ainda não possuí lançamentos</p>
                    @endif
                    <canvas id="chart-category-to-budget"></canvas>
                </div>
            </div>
        </article>
    </div>

    <div class="row">
        <article class="col-sm-6 sortable-grid">
            <div class="jarviswidget jarviswidget-color-blue-dark no-padding">
                <header>
                    <div class="widget-header">
                        <span class="widget-icon"> <i class="fa fa-calendar"></i> </span>
                        <h2> Calendário </h2>
                    </div>
                    <div class="widget-toolbar">
                        <div class="btn-group">
                            <button class="btn dropdown-toggle btn-xs btn-default" data-toggle="dropdown">
                                Exibir
                            </button>
                            <ul class="dropdown-menu js-status-update dropdown-menu-right">
                                <li>
                                    <a class="dropdown-item" href="javascript:void(0);" id="mt">Mês</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="javascript:void(0);" id="ag">Semana</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="javascript:void(0);" id="td">Hoje</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </header>

                <!-- widget div-->
                <div>
                    <div class="widget-body no-padding">
                        <!-- content goes here -->
                        <div class="widget-body-toolbar">
                            <div id="calendar-buttons">
                                <div class="btn-group pull-right">
                                    <a href="javascript:void(0)" class="btn btn-default btn-xs" id="btn-prev"><i class="fa fa-chevron-left"></i></a>
                                    <a href="javascript:void(0)" class="btn btn-default btn-xs" id="btn-next"><i class="fa fa-chevron-right"></i></a>
                                </div>
                            </div>
                        </div>

                        <div id="calendar"></div>
                        <!-- end content -->
                    </div>
                <!-- end widget div -->
                </div>
            <!-- end widget -->
        </article>

        <article class="col-sm-6 sortable-grid ui-sortable">
            <div class="jarviswidget jarviswidget-color-blue-dark no-padding">
                <header role="heading" class="">
                    <div class="widget-header">
                        <span class="widget-icon"> <i class="fa fa-credit-card"></i> </span>
                        <h2>Cartões de crédito</h2>
                    </div>
                    <!--
                    <ul id="myTab" class="nav nav-tabs ml-auto in">
                        <li class="nav-item">
                            <a href="#s1" data-toggle="tab" aria-expanded="false" class="nav-link active">Faturas abertas</a>
                        </li>
                        <li class="nav-item">
                            <a href="#s2" data-toggle="tab" aria-expanded="false" class="nav-link">Faturas fechadas</a>
                        </li>
                    </ul>
                    -->
                </header>
                <div role="content" style="min-height: 648px">
                    <div id="myTabContent" class="tab-content padding-10">
                        <div class="tab-pane active" id="s1">

                             <div class="">
                                @if($creditCardData)
                                    @foreach($creditCardData as $creditCard)
                                        <div>
                                            <h4><b>{{ $creditCard['brand_name'] }}</b> {{ $creditCard['name'] }}</h4>
                                            Vence em {{ $creditCard['closesIn'] }} <br />
                                            R$ {{ $creditCard['maskPartialValue'] }} <br />
                                            <a href="/credit-card/{{$creditCard['id']}}" class="font-xs">Visualizar</a>
                                        </div>
                                        <hr />
                                    @endforeach
                                <h5>TOTAL <span class="pull-right"> R$ {{ $creditCardTotal }}</span></h5>
                                @else
                                    <p>Você ainda não possuí cartões cadastrados.</p>
                                @endif
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </article>
    </div>

</section>

@endsection

@section('scripts')
    @include(
    'layouts/_dashboard_scripts',
    compact(
        $jsonLabel,
        $jsonValue,
        $jsonParentLabel,
        $jsonParentValue
        )
    )
<script type="text/javascript" src="{{ asset('assets/js/custom/Dashboard.js') }}"></script>
@endsection
