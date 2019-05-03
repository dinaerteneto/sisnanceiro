<div class="modal-dialog modal-lg" role="document" id="<?= time() ?>">

    <div class="modal-content">

        <div class="modal-header">
            <h4 class="modal-title">{{ $title }}</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                <span aria-hidden="true">×</span>                
            </button>
        </div>
            
        <form id="event-form" class="" method="post" action="{{ $action }}">
            @csrf

            <div class="modal-body">

                <fieldset>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <input type="text" name="Event[name]" value="{{ $model->name }}" id="Event_name" class="form-control" placeholder="Nome do evento" />
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
                                            <input class="form-control datepicker" id="Event_start_date" value="{{ $model->start_date }}" type="text" placeholder="Data inicial" name="Event[start_date]">
                                            <span class="input-group-append"><span class="input-group-text"><i class="fa fa-calendar"></i></span></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-5">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <input class="form-control hasClockpicker" id="Event_start_time" value="{{ $model->start_time }}" type="text" placeholder="Hora do início" data-autoclose="true" name="Event[start_time]">
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
                                            <input class="form-control datepicker" id="from" value="{{ $model->end_date }}" type="text" placeholder="Data do fim" name="Event[end_date]">
                                            <span class="input-group-append"><span class="input-group-text"><i class="fa fa-calendar"></i></span></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-5">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <input class="form-control hasClockpicker" id="Event_end_time" value="{{ $model->end_time }}" type="text" placeholder="Hora do término" data-autoclose="true" name="Event[end_time]">
                                            <span class="input-group-append"><span class="input-group-text"><i class="fa fa-clock-o"></i></span></span>
                                        </div>             
                                    </div>
                                </div> 
                            </div>                       
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <textarea name="Event[description]" id="Event_description" class="form-control" placeholder="Descrição">{{ $model->description }}</textarea>
                        </div>
                    </div>

                </fieldset>

                <fieldset>
                    <legend>Valores / Limite</legend>

                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Valor por pessoa</label>
                                <input type="text" name="Event[value_per_person]" value="{{ $model->value_per_person }}" id="Event_value_per_person" class="form-control mask-float" placeholder="Valor por pessoa" />
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Limite de pessoas</label>
                                <span class="ui-spinner ui-corner-all ui-widget ui-widget-content" style="height: 32px;">
                                    <input class="form-control spinner-left ui-spinner-input spinner" id="Event_people_limit" name="Event[people_limit]" value="250" aria-valuemin="4" aria-valuemax="10" aria-valuenow="5" autocomplete="off" role="spinbutton">
                                    <a tabindex="-1" aria-hidden="true" class="ui-spinner-button ui-spinner-up ui-corner-tr"></a>
                                    <a tabindex="-1" aria-hidden="true" class="ui-spinner-button ui-spinner-down ui-corner-br"></a>
                                </span>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Convidados por pessoa</label>
                                <span class="ui-spinner ui-corner-all ui-widget ui-widget-content" style="height: 32px;">
                                    <input class="form-control spinner-left ui-spinner-input spinner" id="Event_guest_limit_per_person" name="Event[guest_limit_per_person]" value="4" aria-valuemin="4" aria-valuemax="10" aria-valuenow="5" autocomplete="off" role="spinbutton">
                                    <a tabindex="-1" aria-hidden="true" class="ui-spinner-button ui-spinner-up ui-corner-tr"></a>
                                    <a tabindex="-1" aria-hidden="true" class="ui-spinner-button ui-spinner-down ui-corner-br"></a>
                                </span>
                            </div>
                        </div>
                    </div>

                </fieldset>

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
                        <div class="col-sm-3">
                            <div class="form-group">
                                <input type="text" name="Event[city]" value="{{ $model->city }}" id="Event_city" class="form-control" placeholder="Cidade" />
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <input type="text" name="Event[district]" value="{{ $model->district }}" id="Event_district" class="form-control" placeholder="Estado" />
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <input type="text" name="Event[complement]" value="{{ $model->complement }}" id="Event_complement" class="form-control" placeholder="Complemento" />
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <input type="text" name="Event[reference]" value="{{ $model->reference }}" id="Event_reference" class="form-control" placeholder="Referência" />
                            </div>
                        </div>
                    </div>
                </fieldset>

                <div id="messages" class="has-error"></div>

            </div>

            <div class="modal-footer">
                @if(!empty($model->id))
                <div class="align-left col-sm-6"> 
                    <a href="/event/guest/{{$model->id}}" class="btn btn-success">Convidados</a>
                    <a href="/event/delete/{{$model->id}}" class="btn btn-danger delete-record" data-title="Excluir evento" data-ask="Tem certeza que deseja excluir este evento?">Excluir evento</a>
                </div>
                @endif
                <div class="align-right col-sm-6">
                    <button type="submit" class="btn btn-primary">Alterar</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                </div>
            </div>

        </form>

    </div>

</div>

@include('layouts/_partial_scripts')
<script type="text/javascript" src="{{ asset('assets/js/custom/Event.js') }}"></script>