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

<article class="col-12 sortable-grid ui-sortable">
    <div class="jarviswidget jarviswidget-color-blue-dark jarviswidget-sortable">
        <header role="heading" class="ui-sortable-handle">
            <div class="widget-header">
                <span class="widget-icon"> <i class="fa fa-calendar"></i> </span>
                <h2>Normal Table</h2>
            </div>
        </header>

        <ul id="myTab1" class="nav nav-tabs pull-right in">
            <li class="">
                <a href="#s1" data-toggle="tab" aria-expanded="false">Receitas</a>
            </li>
            <li class="">
                <a href="#s2" data-toggle="tab" aria-expanded="false">Despesas</a>
            </li>
        </ul>

        <div role="content">

            <div id="myTabContent1" class="tab-content padding-10">
                <div class="tab-pane fade" id="s1">
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
                                <td>

                                </td>
                            </tr>
                            @if (isset($toReceive['children']))
                            @foreach ($toReceive['children'] as $child)
                            <tr>
                                <td>
                                    <div style="padding-left: 20px">{{ $child['text'] }}</div>
                                </td>
                                <td>
                                </td>
                            </tr>
                            @endforeach
                            @endif
                            @endforeach
                        </tbody>
                    </table>
                    @endif
                </div>

                <div class="tab-pane fade" id="s2">
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
                                <td>

                                </td>
                            </tr>
                            @if (isset($toPay['children']))
                            @foreach ($toPay['children'] as $child)
                            <tr>
                                <td>
                                    <div style="padding-left: 20px">{{ $child['text'] }}</div>
                                </td>
                                <td>
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