@extends('layouts.app')

@section('content')
<div class="d-flex w-100 home-header">
    <div>
        <h1 class="page-header"><i class="fa-fw fa fa-home"></i> Home <span>&gt; Bem vindo</span></h1>
    </div>
</div>

<article class="col-12 sortable-grid ui-sortable">
    <div class="jarviswidget jarviswidget-color-blue-dark no-padding">
        <header>
            <div class="widget-header">
                <span class="widget-icon"> <i class="fa-fw fa fa-home"></i> </span>
                <h2> Bem vindo </h2>						
            </div>
        </header>

        <div class="widget-body">
            <div class="col-sm-12">
                Olá, ainda estamos construindo o sistema. <br>
                Mas fique a vontade.
            </div>
        </div>
    </div>
</article>

@endsection