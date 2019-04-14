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
                <span class="hidden-mobile hiddex-xs">Já tem uma conta?</span>
                <a href="{{ route('login') }}" class="btn sa-btn-danger">Fazer login</a>
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
                                <div class="col col-lg-7 d-lg-block d-none">
                                    <h1 class="text-red login-header-big">{{ config('app.name', 'Laravel') }}</h1>
                                    <div class="clearfix">
                                        <div class="hero">
                                            <div class="pull-left login-desc-box-l">
                                                <h4 class="paragraph-header">
                                                    Tudo bem ser esperto. Experimente a simplicidade do {{ config('app.name', 'Laravel') }}, onde quer que você vá!
                                                </h4>
                                                <!--
                                                <div class="login-app-icons">
                                                    <a href="javascript:void(0);" class="btn sa-btn-danger btn-sm">Frontend Template</a>
                                                    <a href="javascript:void(0);" class="btn sa-btn-danger btn-sm">Find out more</a>
                                                </div>
                                                -->
                                            </div>

                                            <img src="assets/img/demo/iphoneview.png" class="pull-right display-image" alt="" style="width:210px">

                                        </div>
                                    </div>

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

                                </div>
                                <div class="col-12 col-lg-5">
                                    <div class="well no-padding">
                                        <form method="POST" action="{{ route('register') }}" aria-label="{{ __('Register') }}" class="smart-form client-form" novalidate="novalidate">
                                            @csrf
                                            <header>Cadastre-se</header>

                                            <fieldset>
                                                <section class="mb-3">
                                                    <label class="input">
                                                        <i class="icon-append fa fa-user"></i>
                                                        <input id="name" placeholder="Nome da empresa" type="text" class="form-control{{ $errors->has('company_name') ? ' is-invalid' : '' }}" name="company_name" value="{{ old('company_name') }}" required autofocus>

                                                        @if ($errors->has('company_name'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('company_name') }}</strong>
                                                        </span>
                                                        @endif
                                                    </label>
                                                </section>
                                                <section class="mb-3">
                                                    <i class="icon-append fa fa-envelope"></i>
                                                    <input id="email" placeholder="Endereço de e-mail" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required>

                                                    @if ($errors->has('email'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('email') }}</strong>
                                                    </span>
                                                    @endif
                                                </section>
                                                <section class="mb-3">
                                                    <i class="icon-append fa fa-lock"></i>
                                                    <input id="password" type="password" placeholder="Senha" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                                                    @if ($errors->has('password'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('password') }}</strong>
                                                    </span>
                                                    @endif
                                                </section>
                                                <section class="mb-3">
                                                    <i class="icon-append fa fa-lock"></i>
                                                    <input id="password" type="password" placeholder="Confirme sua senha" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                                                    @if ($errors->has('password'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('password') }}</strong>
                                                    </span>
                                                    @endif
                                                </section>
                                            </fieldset>

                                            <fieldset>
                                                <div class="row">
                                                    <section class="col-md-6 mb-3">
                                                        <label class="input">
                                                            <input type="text" name="firstname" placeholder="Nome">
                                                        </label>
                                                    </section>
                                                    <section class="col-md-6 mb-3">
                                                        <label class="input">
                                                            <input type="text" name="lastname" placeholder="Sobrenome">
                                                        </label>
                                                    </section>
                                                </div>
                                                <div class="row">
                                                    <section class="col-md-6 mb-3">
                                                        <label class="select">
                                                            <select name="gender">
                                                                <option value="" selected="" disabled="">Sexo</option>
                                                                <option value="M">Masculino</option>
                                                                <option value="F">Feminino</option>
                                                            </select> <i></i> </label>
                                                    </section>
                                                    <section class="col-md-6 mb-3">
                                                        <label class="input"> <i class="icon-append fa fa-calendar"></i>
                                                            <input type="text" name="request" placeholder="Data de nascimento" class="datepicker" data-dateformat="dd/mm/yy">
                                                        </label>
                                                    </section>
                                                </div>
                                            </fieldset>

                                            <footer>
                                                <button type="submit" class="btn sa-btn-primary">
                                                    {{ __('Register') }}
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

    <script src="{{ asset('assets/vendors/vendors.bundle.js') }}"></script>
    <script src="{{ asset('assets/app/app.bundle.js') }}"></script>

    <script>
        $(function() {
            $('#menu1').metisMenu();
        });
    </script>
</body>

</html>