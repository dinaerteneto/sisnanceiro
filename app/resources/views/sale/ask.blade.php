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
    <link rel="stylesheet" media="screen, print" href="{{ asset('assets/app/custom.bundle.css') }}">
    <link rel="stylesheet" media="screen, print" href="{{ asset('assets/app/form.css') }}">

    <!-- favicons -->
    <link rel="shortcut icon" href="{{ asset('assets/img/favicon/favicon.ico') }}" type="image/x-icon">
    <link rel="icon" href="{{ asset('assets/img/favicon/favicon.ico') }}" type="image/x-icon">
    <style>
    #contact {
        position: relative;
        margin: 20px auto;
        width: 90%;
        padding: 1%;
        -webkit-box-shadow: 0px 1px 22px 0px rgba(50, 50, 50, 0.75);
        -moz-box-shadow: 0px 1px 22px 0px rgba(50, 50, 50, 0.75);
        box-shadow: 0px 1px 22px 0px rgba(50, 50, 50, 0.75);
        min-height: 85vh;    
    }       

    #contact .teclas {
        position: absolute;
        bottom: 0;
        width: 100%;
        padding: 1%;
        text-align: center;
        background: #E9E9E9;
        left: 0;        
    } 

    .center {
        position: relative;
        -ms-transform: translate(0%, 50%);
        transform: translate(0%, 70%);        
    }    

    </style>
</head>

<body class="">

    <header class="sa-page-header">
        @include('layouts/_header')
    </header>

    <section id="contact" class="home-section text-center">
        <div class="container">
            <div class="center">
                <i class="fa fa-shopping-cart fa-5x text-success"></i>
                <h1 class="text-success">VENDA <b>{{ $sale['sale_code'] }}</b> FINALIZADA COM SUCESSO</h1>
                <a id="sale-create" href="/sale/create" class="btn btn-success btn-lg">CRIAR NOVA VENDA</a>
                <a id="sale-print" href="/sale/coupon/{{ $sale['id'] }}" target="_blank" class="btn btn-warning btn-lg">IMPRIMIR CUPOM</a>
                <a id="sale-print-A4" href="/sale/print/{{ $sale['id'] }}" target="_blank" class="btn btn-info btn-lg">IMPRIMIR A4</a>
            </div>
        </div>

        <div class="row">
            <div class="hidden-xs teclas">
                <b>F2</b> = Nova venda <b>|</b> 
                <b>F3</b> = Imprimir cupom <b>|</b>
                <b>F4</b> = Imprimir A4
            </div>                
        </div>

    </section>

@section('scripts')
    <script type="text/javascript" src="{{ asset('assets/vendors/vendors.bundle.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/app/app.bundle.js') }}"></script>    

    <script type="text/javascript">
    $('document').ready(function() {

        $("body").keydown(function(event) {
            if (event.which == 113) { //F2
                var href = $("#sale-create").attr('href');
                window.location.href = href;
            }
            if (event.which == 114) { //F3
                var href = $("#sale-print").attr('href');
                window.open(href, '_blank');
            }
            if (event.which == 115) { //F4
                var href = $("#sale-print-A4").attr('href');
                window.open(href, '_blank');
            }
        });

    });
    </script>

</body>

</html>
