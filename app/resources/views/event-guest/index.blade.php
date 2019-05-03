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
    <div class="sa-wrapper">
        <div class="sa-page-body">
            <div class="sa-content-wrapper">
                <div class="sa-content">

                    <h1>Olá {{ $data['person_name'] }}</h1>

                    <p>Você foi convidado para o evento <span class="label bg-darken text-white">{{ $event['name'] }}</span>, que irá ocorrer no dia {{ $event['start_date_BR'] }} ás {{ $event['start_time'] }}.</p>
                        
                    <div class="row">
                        <div class="col-sm-2">
                            <div class="card sa-status">
                                <div class="card-header who">
                                    <h4>Sua Presença</h4>
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
                                       
                                        
                                        <div class="text-center">
                                            <i class="fa fa-check-circle-o fa-3x text-success"></i>
                                            <br>Presença confirmada em: 15/07/85 as 15:15h
                                            <br><small><a href="">Revogar presença</a></small>
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

                        <div class="col-sm-4">
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
                                    <div class="align-right"><a href="javascript:void(0)" style="margin-top: -18px; display: block">Convidar mais pessoas</a></div>
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
</body>

</html>
