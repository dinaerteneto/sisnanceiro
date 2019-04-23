<div class="modal-dialog modal-lg" role="document">

    <div class="modal-content">

        <form id="event-form" class="" method="post" action="{{ $action }}">
            @csrf

            <div class="modal-header">
                <h4 class="modal-title">{{ $title }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">×</span>                
                </button>
            </div>
            
            <div class="modal-body">

                <fieldset>
                    <div class="row">
                        <div class="col-sm-8">
                            <div class="form-group">
                                <input type="text" name="Event[name]" value="{{ $model->name }}" id="Event_name" class="form-control" placeholder="Nome do evento" />
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <input type="text" name="Event[people_limit]" value="{{ $model->people_limit }}" id="Event_people_limit" class="form-control" placeholder="Limite de pessoas" />
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <label>Data e hora inicial do evento</label>
                            <div class="row">
                                <div class="col-sm-7">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <input class="form-control datepicker" id="Event_start_date" type="text" placeholder="Data inicial" name="Event[start_date]">
                                            <span class="input-group-append"><span class="input-group-text"><i class="fa fa-calendar"></i></span></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-5">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <input class="form-control hasClockpicker" id="Event_start_time" type="text" placeholder="Hora do início" data-autoclose="true" name="Event[start_time]">
                                            <span class="input-group-append"><span class="input-group-text"><i class="fa fa-clock-o"></i></span></span>
                                        </div>             
                                    </div>
                                </div> 
                            </div>                       
                        </div>
                        <div class="col-sm-6">
                            <label class="align-right">Data e hora final do evento</label>
                            <div class="row">
                                <div class="col-sm-7">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <input class="form-control datepicker" id="from" type="text" placeholder="Data do fim" name="Event[end_date]">
                                            <span class="input-group-append"><span class="input-group-text"><i class="fa fa-calendar"></i></span></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-5">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <input class="form-control hasClockpicker" id="Event_end_time" type="text" placeholder="Hora do término" data-autoclose="true" name="Event[end_time]">
                                            <span class="input-group-append"><span class="input-group-text"><i class="fa fa-clock-o"></i></span></span>
                                        </div>             
                                    </div>
                                </div> 
                            </div>                       
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <textarea name="Event[description]" id="Event_description" class="form-control" placeholder="Descrição"></textarea>
                        </div>
                    </div>

                <fieldset>
                    <legend>Local do evento</legend>
                    
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group">
                                <input type="text" name="Event[zipcode]" value="{{ $model->zipcode }}" id="Event_zipcode" class="mask-cep form-control" placeholder="Cep" data-mask="99999-999" />
                            </div>
                        </div>                        
                        <div class="col-sm-7">
                            <div class="form-group">
                                <input type="text" name="Event[address]" value="{{ $model->address }}" id="Event_address" class="form-control" placeholder="Endereço" />
                            </div>
                        </div>                        
                        <div class="col-sm-2">
                            <div class="form-group">
                                <input type="text" name="Event[address_number]" value="{{ $model->address_number }}" id="Event_address_number" class="form-control" placeholder="Número" />
                            </div>
                        </div>                        
                    </div>

                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <input type="text" name="Event[city]" value="{{ $model->city }}" id="Event_city" class="form-control" placeholder="Cidade" />
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <input type="text" name="Event[complement]" value="{{ $model->complement }}" id="Event_complement" class="form-control" placeholder="Complemento" />
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <input type="text" name="Event[reference]" value="{{ $model->reference }}" id="Event_reference" class="form-control" placeholder="Referência" />
                            </div>
                        </div>
                    </div>
                </fieldset>

                <div id="messages" class="has-error"></div>

            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Enviar</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            </div>

        </form>

    </div>

</div>

<script type="text/javascript" src="{{ asset('assets/js/custom/Event.js') }}"></script>