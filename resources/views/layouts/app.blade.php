<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <base href="{{ config('app.url') }}"/>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">

    <meta name="author" content="Daan Wilson">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') | {{ config('app.name') }}</title>
    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
<?php /* <link href="client/template/metisMenu.min.css" rel="stylesheet"> */?>

<!-- Custom CSS -->
    <link href="css/theme/sb-admin-2.css" rel="stylesheet">
    <link href="css/app.css?{{ filemtime('css/app.css') }}" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="fonts/font-awesome-5.7.2/css/all.min.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>
<div id="app">
    @include('layouts.status')
    @yield('body')
</div>

<!-- jQuery -->
<script src="js/vue/vue.dev.js"></script>
<script src="js/jquery/jquery.min.js"></script>


<!-- Bootstrap Core JavaScript -->
<script src="js/bootstrap/bootstrap.min.js"></script>

<!-- Select2 plugin -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>

<!-- Metis Menu Plugin JavaScript -->
<script src="js/theme/metisMenu.min.js"></script>
<script src="js/vue.objects.js?{{filemtime("js/vue.objects.js")}}"></script>
<script src="js/plugins.js"></script>

<!-- Morris Charts JavaScript -->
<!-- Custom Theme JavaScript -->
<script src="js/theme/sb-admin-2.js"></script>
<script src="js/site.js?{{filemtime("js/site.js")}}"></script>
</body>
</html>