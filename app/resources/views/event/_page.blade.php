<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link rel="stylesheet" media="screen, print" href="{{ asset('assets/vendors/vendors.bundle.css') }}">
    <link rel="stylesheet" media="screen, print" href="{{ asset('assets/pages/LadingPage/bootstrap.min.css') }}">
    <link rel="stylesheet" media="screen, print" href="{{ asset('assets/pages/LadingPage/main.css') }}">

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,300,400,500,700">

    <!-- favicons -->
    <link rel="shortcut icon" href="{{ asset('assets/img/favicon/favicon.ico') }}" type="image/x-icon">
    <link rel="icon" href="{{ asset('assets/img/favicon/favicon.ico') }}" type="image/x-icon">

<script type="text/javascript" src="{{ asset('assets/vendors/vendors.bundle.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/app/app.bundle.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/libs/jquery.maskMoney.0.2.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/custom/form.js') }}"></script>



</head>
<body data-spy="scroll">
    <div class="container">
        <ul id="gn-menu" class="gn-menu-main">
            <li class="hidden-xs">
                <a href="index.html"><img src="img/logo.png" alt="logo" style="width: 135px; margin-top: -4px;"></a>
            </li>
            <li>
                <ul class="company-social">
                    <!--
                    <li class="social-facebook">
                        <a href="javascript:void(0);" target="_blank"><i class="fa fa-facebook"></i></a>
                    </li>
                    <li class="social-twitter">
                        <a href="javascript:void(0);" target="_blank"><i class="fa fa-twitter"></i></a>
                    </li>
                    <li class="social-dribble">
                        <a href="javascript:void(0);" target="_blank"><i class="fa fa-dribbble"></i></a>
                    </li>
                    <li class="social-google">
                        <a href="javascript:void(0);" target="_blank"><i class="fa fa-google-plus"></i></a>
                    </li>
                    -->
                </ul>
            </li>
        </ul>
    </div>


    <section id="contact" class="home-section text-center">
			<div class="heading-contact marginbot-50">
				<div class="container">
					<div class="row">
						<div class="col-lg-8 col-lg-offset-2">

							<div class="section-heading">
								<h2>{{ $data['name'] }}</h2>
								<p>
									Preencha os dados abaixo para confirmar ou negar sua presença no evento.
								</p>
							</div>

						</div>
					</div>
				</div>
			</div>
			<div class="container">

				<div class="row">
					<div class="col-lg-8 col-md-offset-2">
						<div class="boxed-grey">
                            <form id="page-form" method="post" action="/event/{{ $data['id'] }}/page">
                                @csrf
                                <input type="hidden" name="EventGuest[status]" value="" id="EventGuest_status">
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<div class="input-group">
												<span class="input-group-addon"><i class="fa fa-user"></i> </span>
												<input type="text" class="form-control" placeholder="Seu nome" required="required" name="EventGuest[name]">
											</div>
										</div>
										<div class="form-group">
											<div class="input-group">
												<span class="input-group-addon"><i class="fa fa-user"></i> </span>
												<input type="text" class="form-control" placeholder="Nome do Aluno" required="required" name="EventGuest[student_name]">
											</div>
										</div>
										<div class="form-group">
											<div class="input-group">
												<span class="input-group-addon"><i class="fa fa-phone"></i> </span>
												<input type="text" class="mask-cellphone form-control" placeholder="Seu whatsapp" required="required" name="EventGuest[whatsapp]">
											</div>
										</div>
										<div class="form-group">
											<div class="input-group">
												<span class="input-group-addon"><span class="fa fa-envelope"></span> </span>
												<input type="email" class="form-control" placeholder="Seu e-mail" required="required" name="EventGuest[email]">
											</div>
										</div>
									</div>
									<div class="col-md-6">
                                        <address class="text-right">
                                            <strong>INFORMAÇÕES DO EVENTO</strong>
                                            <br>
                                            <i class="fa fa-calendar"></i> {{ $data['start_date_BR'] }} as {{ $data['start_time'] }}h
                                            <br>
                                            <address>
                                                <br>
                                                {{ $data['address'] }}, {{ $data['address_number'] }}
                                                @if(!empty($data['complement']))
                                                <br> {{ $data['complement'] }}
                                                @endif
                                                <br>
                                                {{ $data['district'] }} - {{ $data['city'] }} - {{ $data['uf'] }}
                                                <br>
                                                {{ $data['zipcode'] }}
                                                @if(!empty($data['reference']))
                                                <br> {{ $data['reference'] }}
                                                @endif
                                            </address>
                                            <br>
                                            <address>
                                                <strong>CONTATOS</strong>
                                                @if(!empty($data['email']))
                                                <br><a href="mailto:{{$data['email']}}">{{$data['email']}}</a>
                                                @endif
                                                @if(!empty($data['phone']))
                                                <br>{{$data['phone']}}
                                                @endif
                                                @if(!empty($data['whatsapp']))
                                                <br>{{$data['whatsapp']}}</a>
                                                @endif

                                            </address>
                                        </address>


									</div>
									<div class="col-md-12">
                                        <div class="text-left">
										<button type="button" class="btn btn-skin" value="2">
											<i class="fa fa-thumbs-o-up"></i> Confirmar
										</button>
										<button type="button" class="btn btn-skin" value="3">
											<i class="fa fa-thumbs-o-down"></i> Negar
                                        </button>
                                        </div>
									</div>
								</div>
							</form>
						</div>

					</div>

				</div>


                <div class="row">
                    <div class="col-lg-12">
                        <br>
                        <div class="col-lg-8 col-lg-offset-2">
                            {{ $data['description'] }}
                        </div>
                    </div>
                </div>
			</div>
    </section>
</div>
</html>

<script type="text/javascript">
Page = {
    init: function() {
        Page.setStatus();
        Page.formValidate();
    },

    setStatus: function() {
        $('.btn').on('click', function(e) {
            e.preventDefault();
            var status = $(this).attr('value');
            $('#EventGuest_status').val(status);
            $('#page-form').trigger('submit');
        })
    },

    onSubmit: function() {
        
        $.post($('#page-form').attr('action'), $('#page-form').serialize(), function(json) {
            var color = "#C46A69";
            var title = "Erro!";
            if(json.success) {
                color = "#659265";
                title = "Sucesso!";
            }
            alert(json.message);
            $('#page-form').trigger('reset');
        }, 'json');
        
    },

    formValidate: function() {
        $('#page-form').validate({
            submitHandler: function(form) {
                Page.onSubmit();
            },
            rules: {
                'EventGuest[name]': 'required',
                'EventGuest[student_name]': 'required',
                'EventGuest[whatsapp]': 'required',
                'EventGuest[email]': 'required',
            },
            messages: {
                'EventGuest[name]': 'Obrigatório',
                'EventGuest[student_name]': 'Obrigatório',
                'EventGuest[whatsapp]': 'Obrigatório',
                'EventGuest[email]': 'Obrigatório',
            },
            highlight: function(element) {
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
}

$('document').ready(function() {
    Page.init();
})
</script>
