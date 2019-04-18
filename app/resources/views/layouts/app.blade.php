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

    <!-- favicons -->
    <link rel="shortcut icon" href="{{ asset('assets/img/favicon/favicon.ico') }}" type="image/x-icon">
    <link rel="icon" href="{{ asset('assets/img/favicon/favicon.ico') }}" type="image/x-icon">
</head>

<body class="   smart-style-0">

    <div id="remoteModal" class="modal fade" role="dialog"></div>

    <!-- BEGIN .sa-wrapper -->
    <div class="sa-wrapper">
        <!-- BEGIN .sa-shortcuts -->

        <div class="sa-shortcuts-section">
            <ul>
                <li><a class="bg-pink-dark" href="profile.html"><span class="fa fa-user fa-4x"></span><span class="box-caption">Minha conta</span><em class="counter"></em></a></li>
            </ul>
        </div>
        <!-- END .sa-shortcuts -->

        <header class="sa-page-header">
            @include('layouts/_header')
        </header>

    </div>

    <div class="sa-page-body">

        <!-- BEGIN .sa-aside-left -->

        <div class="sa-aside-left">
            @include('layouts/_menu')
        </div>

        <!-- BEGIN .sa-content-wrapper -->
        <div class="sa-content-wrapper">
            <!-- BEGIN .sa-page-breadcrumb -->
            <ol class="align-items-center sa-page-ribbon breadcrumb" aria-label="breadcrumb" role="navigation">
                <li><span id="refresh" class="btn sa-ribbon-btn sa-theme-btn" data-action="resetWidgets"><i class="fa fa-refresh"></i></span></li>
                <li class="breadcrumb-item"><a href="javascript:void(0)">Miscellaneous</a></li>
                <li class="breadcrumb-item"><a href="blank.html">Blank Page</a></li>
            </ol>

            <!-- END .sa-page-breadcrumb -->
            <div class="sa-content">
                @yield('content')
            </div>

            <!-- BEGIN .sa-page-footer -->
            <footer class="sa-page-footer">
                @include('layouts/_footer')
            </footer>
            <!-- END .sa-page-footer -->

        </div>
        <!-- END .sa-content-wrapper -->
    </div>

    <script src="{{ asset('assets/vendors/vendors.bundle.js') }}"></script>
    <script src="{{ asset('assets/app/app.bundle.js') }}"></script>

    <script src="{{ asset('assets/js/custom/delete.js') }}"></script>
    <script src="{{ asset('assets/js/custom/form.js') }}"></script>
    <script src="{{ asset('assets/js/custom/main.js') }}"></script>

    <script>
        $(function() {
            $('#menu1').metisMenu();
        });
    </script>

</body>

</html>