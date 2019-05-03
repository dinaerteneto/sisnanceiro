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
            <div class="widget-toolbar ml-auto">
                <!-- add: non-hidden - to disable auto hide -->
                <div class="btn-group">
                    <button class="btn dropdown-toggle btn-xs btn-default" data-toggle="dropdown">
                        Exibir
                    </button>
                    <ul class="dropdown-menu js-status-update pull-right">
                        <li>
                            <a href="javascript:void(0);" id="mt">Mês</a>
                        </li>
                        <li>
                            <a href="javascript:void(0);" id="ag">Agenda</a>
                        </li>
                        <li>
                            <a href="javascript:void(0);" id="td">Hoje</a>
                        </li>
                    </ul>
                </div>
            </div>
        </header>

        <!-- widget div-->
        <div>
            

            <div class="widget-body no-padding">
                <!-- content goes here -->

                        <div class="col-sm-12 pull-right align-right">
                            <a 
                                href="/event/create"
                                class = "open-modal"
                                target = "#remoteModal"
                                rel = "tooltip"
                                data-placement = "top"
                                title = "Criar evento"                                       
                            >
                                <i class="fa fa-plus"></i> NOVO EVENTO
                            </a>
                        </div>

                <div class="widget-body-toolbar text-right">

                    <div id="calendar-buttons">
                        <div class="btn-group">
                            <a href="javascript:void(0)" class="btn btn-default btn-xs" id="btn-prev"><i class="fa fa-chevron-left"></i></a>
                            <a href="javascript:void(0)" class="btn btn-default btn-xs" id="btn-next"><i class="fa fa-chevron-right"></i></a>
                        </div>
                    </div>
                </div>

                <div id="calendar"></div>
                <!-- end content -->
            </div>
        </div>
        <!-- end widget div -->
    </div>
    <!-- end widget -->
	
</article>
@section('scripts')
<script type="text/javascript" src="{{ asset('assets/js/custom/Event.js') }}"></script>
@stop

@endsection
