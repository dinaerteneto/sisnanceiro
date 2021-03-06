@extends('layouts.app')

@section('content')

<div class="d-flex w-100 home-header">
    <div>
        <h1 class="page-header"><i class="fa fa-fw fa-calendar "></i> Calendário <span>&gt; Eventos</span></h1>
    </div>
</div>

<article class="col-12 sortable-grid">
    <div class="jarviswidget jarviswidget-color-blue-dark no-padding">
        <header>
            <div class="widget-header">
                <span class="widget-icon"> <i class="fa fa-calendar"></i> </span>
                <h2> Eventos </h2>
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
@section('scripts')
<script type="text/javascript" src="{{ asset('assets/js/custom/Event.js') }}"></script>
@stop

@endsection
