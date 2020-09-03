<!DOCTYPE html>

<html lang="en" class="smart-style-0">

    <head>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-177021906-1"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'UA-177021906-1');
    </script>

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
                                                    Tudo bem ser esperto. Experimente a simplicidade do {{ config('app.name', 'Laravel') }}, onde quer que você vá!
                                                </h4>

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

                                    <!--
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                            <h5 class="about-heading">Sobre o {{ config('app.name', 'Laravel') }} - Você está atualizado?</h5>
                                            <p>
                                                Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa.
                                            </p>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                            <h5 class="about-heading">Not just your average template!</h5>
                                            <p>
                                                Et harum quidem rerum facilis est et expedita distinctio. Nam libero tempore, cum soluta nobis est eligendi voluptatem accusantium!
                                            </p>
                                        </div>
                                    </div>
                                    !-->

                                </div>
                                <div class="col-sm-12 col-lg-4">
                                    <div class="well no-padding">
                                        <form method="POST" action="{{ route('login') }}" aria-label="{{ __('Login') }}" id="login-form" class="smart-form client-form">
                                            @csrf
                                            <header>Login</header>
                                            <fieldset>
                                                <section>
                                                    <label for="email" class="">E-Mail / Login</label>
                                                    <label class="input mb-3">
                                                        <i class="icon-append fa fa-user"></i>
                                                        <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus>
                                                        <b class="tooltip tooltip-bottom-right"><i class="fa fa-user txt-color-teal"></i> Digite seu e-mail/login</b>
                                                        @if ($errors->has('email'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('email') }}</strong>
                                                        </span>
                                                        @endif
                                                    </label>
                                                </section>
                                                <section>
                                                    <label for="password" class="">Senha</label>
                                                    <label class="input mb-1">
                                                        <i class="icon-append fa fa-lock"></i>
                                                        <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>
                                                        <b class="tooltip tooltip-bottom-right"><i class="fa fa-lock txt-color-teal"></i> Digite sua senha</b>
                                                        @if ($errors->has('password'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('password') }}</strong>
                                                        </span>
                                                        @endif
                                                    </label>
                                                    <div class="note mb-3">
                                                        <a class="" href="{{ route('password.request') }}">
                                                            {{ __('Esqueci minha senha') }}
                                                        </a>
                                                    </div>
                                                </section>
                                                <section>
                                                    <label for="gra-0" class="vcheck mb-3">
                                                        <input class="form-check-input" type="checkbox" name="remember" id="gra-0" checked="checked" {{ old('remember') ? 'checked' : '' }}>
                                                        <span></span> {{ __('Lembrar-me') }}
                                                    </label>
                                                </section>
                                            </fieldset>
                                            <footer>
                                                <button type="submit" class="btn sa-btn-primary">
                                                    {{ __('Login') }}
                                                </button>
                                            </footer>

                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/vendors/vendors.bundle.js') }}"></script>
    <script src="{{ asset('assets/app/app.bundle.js') }}"></script>

    <script>
        $(function() {
            $('#menu1').metisMenu();
        });
    </script>
</body>

</html>
