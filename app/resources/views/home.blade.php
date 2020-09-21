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
        <article class="col-sm-6">
            <div class="jarviswidget jarviswidget-color-blueDark">
                <header class="ui-sortable-handle">
                    <div class="widget-header">
                        <span class="widget-icon"> <i class="fa fa-arrow-circle-down text-red"></i> </span>
                        <h2>Contas a pagar por categorias</h2>
                    </div>
                </header>
                <div class="widget-body" style="position: relative; height:50vh">
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
                <div class="widget-body" style="position: relative; height:50vh">
                    <canvas id="chart-category-to-budget"></canvas>
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
@endsection
