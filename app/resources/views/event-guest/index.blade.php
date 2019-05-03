<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,300,400,500,700">

    <!-- Styles -->
    <link rel="stylesheet" media="screen, print" href="{{ asset('assets/vendors/vendors.bundle.css') }}">
    <link rel="stylesheet" media="screen, print" href="{{ asset('assets/app/app.bundle.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/pages/homepage.css') }}">

    <!-- favicons -->
    <link rel="shortcut icon" href="{{ asset('assets/img/favicon/favicon.ico') }}" type="image/x-icon">
    <link rel="icon" href="{{ asset('assets/img/favicon/favicon.ico') }}" type="image/x-icon">
</head>

<body class="no-header   smart-style-0  pace-done">

    <div class="modal fade" id="form-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h4 class="modal-title">Enviar convite</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                        <span aria-hidden="true">×</span>                
                    </button>
                </div>
                
                    <form id="form-1" method="post" action="/guest/{{ $data['id'] }}/send-invite">
                        @csrf

                        <div class="modal-body">
                            <p>O seu convidado irá receber um e-mail, informando que você o(a) esta convidado para partipar deste mesmo evento.</p>

                            <fieldset>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <input type="text" required name="EventGuest[person_name]" id="EventGuest_person_name" class="form-control" placeholder="Nome do convidado" />
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <input type="email" required name="EventGuest[email]" id="EventGuest_email" class="form-control" placeholder="E-mail do convidado" />
                                        </div>
                                    </div>
                                </div>

                                @if(!empty($event['value_per_person']) && $event['value_per_person'] > 0)
                                <fieldset>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label>Quem será o responsável pelo pagamento de R$ {{ $event['value_per_person'] }}?</label>
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <label class="radio">
                                                            <input type="radio" value="me" checked="checked" name="EventGuest[reponsable_of_payment]">
                                                            <i></i>
                                                            Eu
                                                        </label>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <label class="radio">
                                                            <input type="radio" value="invite" name="EventGuest[reponsable_of_payment]">
                                                            <i></i>
                                                            Convidado
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>                                    
                                    </div>
                                </fieldset>
                                @endif

                            </fieldset>
                        </div>

                        <div class="modal-footer">

                            <div class="align-right col-sm-6">
                                <button type="submit" class="btn btn-primary">Enviar</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                            </div>
                        </div>

                    </form>
                

            </div>
        </div>
    </div>


    <div class="sa-wrapper">
        <div class="sa-page-body">
            <div class="sa-content-wrapper">
                <div class="sa-content">

                    <h1>Olá {{ $data['person_name'] }}</h1>

                    <p>Você foi convidado para o evento <span class="label bg-darken text-white">{{ $event['name'] }}</span>, que irá ocorrer no dia {{ $event['start_date_BR'] }} ás {{ $event['start_time'] }}.</p>
                        
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="card sa-status">
                                <div class="card-header who">
                                    <h4><i class="fa fa-check-circle-o text-success"></i> Sua Presença</h4>
                                </div>
                                <div class="card-body p-0">		
                                    <div class="who clearfix">
                                        <!--
                                        <div class="text-center">
                                            <a href="">
                                                <i class="fa fa-thumbs-o-up fa-4x text-info"></i>
                                                <br>Clique para confirmar sua presença.
                                            </a>
                                        </div>
                                        -->
                                       
                                        
                                        <div class="row">
                                            <br>Confirmada em: 15/07/85 as 15:15h
                                            <br><small><a href="">Revogar presença</a></small>

                                            <br><strong>Pagto confirmado em: 15/07/85</strong>
                                        </div>
                                        

                                         <!--
                                        <div class="text-center">
                                            <i class="fa fa-times-circle fa-3x text-danger"></i>
                                            <br>Sua presença foi negada em: 15/07/85 as 15:15h
                                            <br><a href="">Aceitar convite</a>
                                        </div>
                                        -->
                                        
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="card sa-status">
                                <div class="card-header who">
                                    <h4>Informações do evento</h4>
                                </div>
                                <div class="card-body p-0">		
                                    <div class="who clearfix">
                                        <address>
                                            <strong>{{ $event['address'] }}, {{ $event['address_number'] }}</strong>
                                            <br>
                                            {{ $event['district'] }}
                                            <br>
                                            {{ $event['city'] }} - {{ $event['uf'] }}
                                            <br>
                                            <abbr title="Phone"><i class="fa fa-phone-square"></i></abbr> 11 2951-0315
                                        </address>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="card sa-status">
                                <div class="card-header who">
                                    <h4>Pessoas que convidei</h4>
                                    <div class="align-right"><a href="javascript:void(0)" data-toggle="modal" data-target="#form-modal" style="margin-top: -18px; display: block">Convidar mais pessoas</a></div>
                                </div>
                                <div class="card-body p-0">	

                                    <div class="who clearfix">
                                        @if($invitedByMe)
                                        <table class="table">
                                            
                                            @foreach($invitedByMe as $invited)
                                                <tr>
                                                    <td>{{ $invited['person_name'] }}</td>
                                                    <td>{{ $invited['email'] }}</td>
                                                    <td>{{ $invited['created_at'] }}</td>
                                                    <td>{{ $invited['status'] }}</td>
                                                </tr>
                                            @endforeach
                                           
                                        </table>                                    
                                        @endif
                                    </div>

                                </div>
                            </div>
                        </div>
                   
					</div>


                </div>
            </div>
        </div>
    </div>


    <script type="text/javascript" src="{{ asset('assets/vendors/vendors.bundle.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/app/app.bundle.js') }}"></script>


    <script type="text/javascript">
    EventGuest = {
        addInvite: function() {

        },

        submitForm: function() {

        },

        validation: function() {

        }
    }
    </script>

</body>

</html>
