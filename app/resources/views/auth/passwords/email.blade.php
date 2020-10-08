<!DOCTYPE html>

<html lang="en" class="smart-style-0">

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
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/pages/login.css') }}">

    <!-- favicons -->
    <link rel="shortcut icon" href="{{ asset('assets/img/favicon/favicon.ico') }}" type="image/x-icon">
    <link rel="icon" href="{{ asset('assets/img/favicon/favicon.ico') }}" type="image/x-icon">
</head>

<body class=" publicHeader-active animated fadeInDown smart-style-0">

    <!-- BEGIN .sa-wrapper -->
    <div class="sa-wrapper">
        <header id="header" class="publicheader">
            <div class="logo-group">
                <span class="sa-logo"> <img src="{{ asset('assets/img/logo.png') }}" alt="{{ config('app.name', 'Laravel') }}"> </span>
            </div>

            <span class="extr-page-header-space">
                <span class="hidden-mobile hiddex-xs">Precisa de uma conta?</span>
                <a href="{{ route('register') }}" class="btn sa-btn-danger">Criar conta</a>
            </span>
        </header>
        <div class="sa-page-body">
            <!-- BEGIN .sa-content-wrapper -->
            <div class="sa-content-wrapper">

                <div class="sa-content">



                    <div class="main" role="main">

                        <!-- MAIN CONTENT -->
                        <div id="content" class="container padding-top-10">

                            <div class="row">
                                <div class="col-sm-12 col-md-7 col-lg-8 hidden-xs hidden-sm">
                                    <h1 class="text-red login-header-big">{{ config('app.name', 'Laravel') }}</h1>
                                    <div class="clearfix">
                                        <div class="">
                                            <div class="pull-left login-desc-box-l">
                                                <h4 class="paragraph-header">
                                                    Esta é a ferramenta que irá lhe ajudar a ter uma vida financeira mais próspera.
                                                </h4>

                                                <p style="margin-top: 10px">
                                                    Baseado nas caixinhas de: Aplicações, Sonhos, Educação, Orçamento familiar e Doações aprenda a classificar seu dinheiro e enriquecer.
                                                </p>
                                                <br>

                                                @if ($message = Session::has('success'))
                                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                                    {!! Session::get('success')['message'] !!}.
                                                </div>
                                                @endif

                                                @if (Session::has('error'))
                                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                    <i class="fa-fw fa fa-times"></i>
                                                    <strong>Erro!</strong> {{ Session::get('error')['message'] }}
                                                    @foreach(Session::get('error')['errors'] as $errors)
                                                        <br>{{$errors['message']}}
                                                    @endforeach
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                        <span aria-hidden="true">×</span>
                                                    </button>
                                                </div>
                                                @endif

                                            </div>

                                            <img src="assets/img/demo/iphoneview.png" class="pull-right display-image" alt="" style="width:210px">

                                        </div>
                                    </div>

                                </div>
                                <div class="col-sm-12 col-lg-4">
                                    <div class="well no-padding">

                                    <form method="POST" action="{{ route('password.email') }}" id="login-form" class="smart-form client-form">
                                            @csrf
                                            <header>
                                                Esqueci minha senha
                                            </header>

                                            <fieldset>

                                                <section>
                                                    <label class="label">Digite seu e-mail</label>
                                                    <label class="input"> <i class="icon-append fa fa-envelope"></i>
                                                        <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required>
                                                        <b class="tooltip tooltip-top-right"><i class="fa fa-envelope txt-color-teal"></i> Digite seu e-mail para criar uma nova senha.</b></label>
                                                        @if ($errors->has('email'))
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $errors->first('email') }}</strong>
                                                            </span>
                                                        @endif
                                                </section>


                                            </fieldset>
                                            <footer>
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fa fa-refresh"></i> Enviar senha por e-mail
                                                </button>
                                            </footer>
                                        </form>

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
