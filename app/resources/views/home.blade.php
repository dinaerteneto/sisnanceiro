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
        <div role="content">
        @if ($people)
            <table class="table">
                <thead>
                    <th>Nome</th>
                    <th>Sexo</th>
                </thead>
                <tbody>
                    @foreach ($people as $person)
                    <tr>
                        <td>{{ $person->name }} {{$person->last_name}}</td>
                        <td>{{ $person->gender}} </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>


</article>

@endsection