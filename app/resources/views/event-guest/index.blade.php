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

                    <form id="form-1" method="post" action="/guest/{{ $data['token_email'] }}/send-invite">
                        @csrf

                        <input type="hidden" name="EventGuest[event_id]" value="{{ $event['id'] }}" />
                        <input type="hidden" name="EventGuest[invited_by_id]" value="{{ $data['id'] }}" />

                        <div class="modal-body">
                            <p>O seu convidado irá receber um e-mail, informando que você o(a) esta convidado para partipar deste mesmo evento.</p>

                            <fieldset>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <input type="text" name="EventGuest[name]" id="EventGuest_name" class="form-control" placeholder="Nome do convidado" />
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <input type="email" name="EventGuest[email]" id="EventGuest_email" class="form-control" placeholder="E-mail do convidado" />
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
                                                            <input type="radio" value="me" checked="checked" name="EventGuest[responsable_of_payment]">
                                                            <i></i>
                                                            Eu
                                                        </label>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <label class="radio">
                                                            <input type="radio" value="invite" name="EventGuest[responsable_of_payment]">
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

                    <p>Você foi convidado para o evento <b>{{ $event['name'] }}</b>, que irá ocorrer no dia {{ $event['start_date_BR'] }} ás {{ $event['start_time'] }}.</p>

                    <div class="row">
                        <div class="col-sm-3">
                            <div class="card sa-status">
                                <div class="card-header who">
                                    <h4>Sua Presença</h4>
                                </div>
                                <div class="card-body p-0">
                                    <div class="who clearfix" id="guest-status">
                                        @if( !$data['canCancel'] )
                                            @include('event-guest/_status_with_payment', compact('data'))
                                        @else
                                            @include('event-guest/_status', compact('data'))
                                        @endif
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
                                            @if (!empty($data['phone']) )
                                                <abbr title="Phone"><i class="fa fa-fa-phone"></i></abbr> {{ $data['phone'] }} <br />
                                            @endif
                                            @if( !empty($data['whatsapp']) )
                                                <abbr title="Phone"><i class="fa fa-mobile-phone"></i></abbr> {{ $data['whatsapp'] }}
                                            @endif
                                        </address>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="card sa-status">
                                <div class="card-header who">
                                    <h4>Informações de pagamento</h4>
                                </div>
                                <div class="card-body p-0">
                                    <div class="who clearfix">
                                        O valor do seu ingresso é de R$ {{ $event['value_per_person'] }}. <br><br>
                                        @if($data['payment_method_id'] == '3' && $data['status'] != 2)
                                        Você informou que seu pagamento será feito com dinheiro. <br>
                                        Caso não tenha efetuado o pagamento e queira mudar a forma de pagamento <a href="/guest/{{ $data['token_email'] }}/invoice" target="_blank">clique aqui</a>.
                                        @else
                                        <a href="/guest/{{ $data['token_email'] }}/invoice" target="_blank">Clique aqui</a> para ir para a tela de pagamento
                                        <br> ou <a href="/guest/{{ $data['token_email'] }}/payment-with-money">clique aqui</a> para informar que o pagamento será feito com dinheiro.
                                        @endif
                                    </div>
                                </div>
                            </div>

                        </div>

					</div>

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card sa-status">
                                <div class="card-header who">
                                    <h4>Pessoas que convidei</h4>
                                    <input type="hidden" value="{{$event['guest_limit_per_person']}}" name="Event[guest_limit_per_person]" id="Event_guest_limit_per_person">
                                    @if(empty($event['guest_limit_per_person']) || ($invitedByMe['total_invited'] < $event['guest_limit_per_person']) )
                                    <div class="align-right"><a href="javascript:void(0)" style="margin-top: -18px; display: block" id="a-add-guest">Convidar mais pessoas</a></div>
                                    @endif
                                </div>
                                <div class="card-body p-0">

                                    <div class="who clearfix">
                                        @if($invitedByMe)
                                        <table class="table" id="table-invited-by-me">
                                            @foreach($invitedByMe as $invited)
                                                @if(is_array($invited))
                                                <tr>
                                                    <td>{{ $invited['person_name'] }}</td>
                                                    <td>{{ $invited['email'] }}</td>
                                                    <td>{{ $invited['created_at'] }}</td>
                                                    <td>{{ $invited['status'] }}</td>
                                                </tr>
                                                @endif
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
        init: function() {
            EventGuest.addGuest();
            EventGuest.submitForm();
            EventGuest.formValidate();
        },

        canGuest: function()
        {
            var guestLimit = parseInt($('#Event_guest_limit_per_person').val());
            var totalGuest = parseInt($('#table-invited-by-me tbody tr').length);
            if(guestLimit <= totalGuest) {
                return false;
            }
            return true;
        },

        addGuest: function()
        {
            $('#a-add-guest').on('click', function(e) {
                e.preventDefault();
                if(EventGuest.canGuest()) {
                    $('#form-modal').modal('toggle');
                } else {
                    $('#a-add-guest').remove();
                    alert('Você não pode convidar mais pessoas.');
                }
            });
            
        },

        submitForm: function() {
            $('#form-1').on('submit', function(e) {
                e.preventDefault();

                if(!$('#form-1').valid()) {
                    return false;
                }

                var data = $('#form-1').serialize();
                var url = $('#form-1').attr('action');
                var method = $('#form-1').attr('method');
                $.ajax({
                    url: url,
                    data: data,
                    method: method,
                    dataType: 'json',
                    success: function(json) {

                        if(json.success) {
                            $.smallBox({
                                title: "Sucesso!",
                                content: "<i class='fa fa-clock-o'></i> <i>Convite enviado com sucesso.</i>",
                                color: "#659265",
                                iconSmall: "fa fa-check fa-2x fadeInRight animated",
                                timeout: 2000
                            });

                            var html = "<tr>";
                                html+= "<td>" + json.data.person_name + "</td>";
                                html+= "<td>" + json.data.email + "</td>";
                                html+= "<td>" + json.data.created_at + "</td>";
                                html+= "<td>" + json.data.status + "</td>";
                                html+= "</tr>";

                            $('#table-invited-by-me').append(html);
                            $('#form-modal').modal('hide');
                            $('#form-1').each(function(){this.reset()});
                        } else {
                            $.smallBox({
                                title: "Erro!",
                                content: "<i class='fa fa-clock-o'></i> <i>" + json.data.message + "</i>",
                                color: "#C46A69",
                                iconSmall: "fa fa-times fa-2x fadeInRight animated",
                                timeout: 8000
                            });

                        }
                    },
                    complete: function() {
                        if(!EventGuest.canGuest()) {
                            $('#a-add-guest').remove();
                        }
                    }
                });
            });
        },

        formValidate: function() {
            $('#form-1').validate({
                rules: {
                    'EventGuest[name]': 'required',
                    'EventGuest[email]': {
                        'required': true,
                        'email': true
                    },
                    'EventGuest[responsable_of_payment]': 'required'
                },
                messages: {
                    'EventGuest[name]': 'Nome é obrigatório.',
                    'EventGuest[email]': {
                        'required': 'Digite o e-mail de seu convidado.',
                        'email': 'Digite um e-mail válido.'
                    },
                    'EventGuest[responsable_of_paymentresponsable_of_payment]': 'Responsável pelo pagto é obrigatório'
                },
                highlight: function(element) {
                    console.log(element);
                    $(element).removeClass('is-valid').addClass('is-invalid');
                    $(element).closest('.form-group').removeClass('has-success').addClass('has-error');
                },
                unhighlight: function(element) {
                    $(element).removeClass('is-invalid').addClass('is-valid');
                    $(element).closest('.form-group').removeClass('has-error').addClass('has-success');
                },
                errorElement: 'span',
                errorClass: 'invalid-feedback',
                errorPlacement: function(error, element) {
                    if (element.parent('.input-group').length) {
                        error.insertAfter(element.parent());
                    } else {
                        error.insertAfter(element);
                    }
                }

            });
        }
    };

    $(function() {
        EventGuest.init();
    });

    </script>

</body>

</html>
