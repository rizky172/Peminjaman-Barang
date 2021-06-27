<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Peminjaman Barang</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="{{ asset('assets/css/old/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/old/adminlte/adminlte.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/old/toastr/toastr.min.css') }}" rel="stylesheet">
  <link href="{{ asset('css/app.css') }}" rel="stylesheet" type='text/css'>
</head>
<body class="hold-transition sidebar-mini">
  <div class="wrapper">
    <div id="app">
    </div>
  </div>
  <script src="{{ asset('js/app.js') }}"></script>
  <script src="{{ asset('assets/js/old/jquery/jquery.min.js') }}"></script>
  <script src="{{ asset('assets/css/old/toastr/toastr.min.js') }}"></script>
  <script src="{{ asset('assets/js/old/adminlte/adminlte.min.js') }}"></script>
  <script src="{{ asset('assets/js/old/adminlte/demo.js') }}"></script>
</body>
</html>
