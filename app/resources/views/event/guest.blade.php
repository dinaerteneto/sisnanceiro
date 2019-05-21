@extends('layouts.app')

@section('content')

<div class="d-flex w-100 home-header">
    <div>
        <h1 class="page-header"><i class="fa fa-fw fa-calendar "></i> Calendário <span>&gt; Eventos</span></h1>
    </div>
</div>

<div class="row">
    <article class="col-sm-12 sortable-grid">
        <div class="jarviswidget jarviswidget-color-blue-dark no-padding">
            <header>
                <div class="widget-header">
                    <span class="widget-icon"> <i class="fa fa-calendar"></i> </span>
                    <h2> Evento </h2>						
                </div>

            </header>

            <!-- widget div-->
            <div>
                <div class="widget-body padding-10">
                    <!-- content goes here -->

                    <div class="row">
                        <div class="col-md-12">
                            <a href="/event/{{ $model->id }}/guests" target="_blank"><i class="fa fa-print"></i> Imprimir lista de convidados</a>
                        </div>
                    </div>

                    <div class="row">
						<div class="col-xl-6 col-md-8">
							<h4 class="text-medium">{{ $model->name }}</h4>
							<address>
								<strong>{{ $model->address }}, {{ $model->address_number }}</strong>
								<br>
								{{ $model->district }}
								<br>
								{{ $model->city }} - {{ $model->uf }}
								<br>
								<abbr title="Phone"><i class="fa fa-phone-square"></i></abbr> 
                            </address>
						</div>
						<div class="col-xl-6 col-md-4">

							<div>
								<div class="font-md">
                                    <p> <i class="fa fa-calendar"></i> {{ $model->start_date_BR }} <i class="fa fa-clock-o"></i> {{ $model->start_time }}</p>
                                </div>
                                
                                <div class="mb-10">
                                    <strong>Lotação:</strong> {{ $model->people_limit }} pessoa<br>
                                    <strong>Convidados por pessoa:</strong> {{ $model->guest_limit_per_person }}<br>
                                </div>

                                <div>
                                    {{ $model->totalGuest['total'] }} Convidados no total <br>
                                    {{ $model->totalGuest['confirmed'] }} Confirmados<br>
                                    {{ $model->totalGuest['waiting'] }} Aguardando confirmação<br>
                                    {{ $model->totalGuest['denied'] }} Negaram <br><br>
                                    @if($model->value_per_person > 0) 
                                    <b>R$ {{ $model->totalGuest['revenue'] }}</b> arrecadados até o momento
                                    @endif
                                </div>
							</div>
							<br>
							<div class="well well-sm bg-darken text-white no-border no-border-radius">
								<div class="fa-lg">
									Por pessoa
									<span class="pull-right"> R$ {{ $model->value_per_person }} </span>
								</div>
							</div>
							<br>
							<br>
						</div>
                    </div>
                    
                    <div class="row">
                        <div class="col-sm-12">
                           {{ $model->description }}
                           <br>                           
                        </div>
                    </div>

                    <!-- end content -->
                </div>
            </div>
            <!-- end widget div -->
        </div>
        <!-- end widget -->
    </article>

    <article class="col-sm-12 sortable-grid">
        <div class="jarviswidget jarviswidget-color-blue-dark no-padding">
            <header>
                <div class="widget-header">
                    <span class="widget-icon"> <i class="fa fa-user"></i> </span>
                    <h2> 
                        Convidados 
                    </h2>			
                </div>
            </header>

            <!-- widget div-->
            <div>
                <div class="widget-body no-padding">
                    <!-- content goes here -->
                    <div role="content">		
							<!-- widget edit box -->
							<div class="jarviswidget-editbox">
								<!-- This area used as dropdown edit box -->
								<input class="form-control" type="text">	
							</div>
							<!-- end widget edit box -->
							
							<!-- widget content -->
							<div class="widget-body overflow-hidden p-0">
								
								<!-- this is what the user will see -->
                                <div class="chat-body custom-scroll">
                                    <div class="mb-10">
                                        <a href="/event/guest/{{$model->id}}/add" 
                                            id="guest-add" 
                                            rel="tooltip" 
                                            data-placement="top" 
                                            title="Adiciona campos para que você possa adicionar convidados"
                                        >
                                            <i class="fa fa-plus"></i> Adicionar convidado
                                        </a>			
                                    </div>

                                    <div class="dropdown">
                                        <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Acões
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <a class="dropdown-item dropdown-status" href="javascript:void(0)" value="1"><i class="fa fa-circle text-yellow"></i> Definir como Aguardando</a>
                                            <a class="dropdown-item dropdown-status" href="javascript:void(0)" value="2"><i class="fa fa-circle text-green"></i> Definir como Confirmado</a>
                                            <a class="dropdown-item dropdown-status" href="javascript:void(0)" value="3"><i class="fa fa-circle text-red"></i> Definir como Recusado</a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item dropdown-status" href="javascript:void(0)" value="re-send-mail"><i class="fa fa-envelope"></i> Reenviar e-mail</a>
                                        </div>
                                    </div> 
                                    
                                    <div class="mb-10"></div>

                                    <form name="form-guest" id="form-guest" action="/event/guest/{{ $model->id }}/create" method="post">
                                        <input name="EventGuest[status]" type="hidden" value="" id="EventGuest_status">
                                        <input name="EventGuest[event_id]" type="hidden" value="{{ $model->id }}" id="EventGuest_event_id">

                                        @csrf
                                        <div id="content-guest"></div>
                                        <button type="submit" id="submit-guest" class="btn sa-btn-success no-margin d-none float-right mb-10" rel="tooltip" data-placement="top" title="Salvar os convidados adicionados">Salvar convidados</button>
                                                                                                                
                                        <table class="table table-hover" id="content-guest">
                                            <thead>
                                                <tr>
                                                    <th>
                                                        <label class="checkbox vcheck">
                                                            <input type="checkbox" name="EventGuest[all]" value="all" class="check-all">
                                                            <span></span>
                                                        </label>
                                                    </th>
                                                    <th>Convidado</th>
                                                    <th>E-mail</th>
                                                    <th>Status</th>
                                                    <th>Convites</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            @if($guests)
                                                @foreach($guests as $guest)
                                                    <?php $child = false ?>
                                                    @include('event/_guest', compact('guest', 'child'))
                                                @endforeach
                                            @endif
                                            </tbody>
                                        </table>

                                    </form>

								</div>

							</div>
							<!-- end widget content -->
							
						</div>
                    <!-- end content -->
                </div>
            </div>
            <!-- end widget div -->
        </div>
        <!-- end widget -->
    </article>
</div>

@section('scripts')
<script type="text/javascript" src="{{ asset('assets/js/custom/EventGuest.js') }}"></script>
@stop

@endsection
