<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Gentelella Alela! | </title>
    <link href="{{ asset('assets/css/bootstrap/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/font/css/font-awesome.min.css') }}" rel="stylesheet" type='text/css'>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet" type='text/css'>
    <link href="{{ asset('assets/css/custom.min.css') }}" rel="stylesheet">
    <!-- <link href="{{ asset('assets/css/nprogress/nprogress.css') }}" rel="stylesheet"> -->
    <!-- <link href="{{ asset('assets/css/animate.css/animate.min.css') }}" rel="stylesheet"> -->
  
      

    
    <!-- <link href="{{ asset('assets/css/bootstrap/bootstrap.min.css') }}" rel="stylesheet"> -->
    <!-- <link href="{{ asset('assets/css/font/css/font-awesome.min.css') }}" rel="stylesheet" type='text/css'> -->
    <!-- <link href="{{ asset('assets/css/datatables/dataTables.bootstrap.min.css') }}" rel="stylesheet"> -->
    <!-- <link href="{{ asset('assets/css/custom.min.css') }}" rel="stylesheet"> -->
  </head>

  <body class="nav-md" style="background: white">
    <div class="container body">
      <div class="main_container">
        <div id="app">
        </div>
      </div>
    </div>
    <!-- <footer>
        <div class="pull-right">
            Gentelella - Bootstrap Admin Template by <a href="https://colorlib.com">Colorlib</a>
        </div>
        <div class="clearfix"></div>
    </footer> -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('assets/js/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap/bootstrap.bundle.min.js') }}"></script>
    <!-- <script src="{{ asset('assets/js/datatables/jquery.dataTables.min.js') }}"></script> -->
    <!-- <script src="{{ asset('assets/js/datatables/dataTables.bootstrap.min.js') }}"></script> -->
    <script src="{{ asset('assets/js/custom.min.js') }}"></script>
  </body>
</html>
