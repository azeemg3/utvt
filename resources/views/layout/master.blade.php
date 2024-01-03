<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>@yield('mytitle') | Tourvision Travel </title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Font Awesome -->
  @include('layout.css')
</head>
<body class="sidebar-mini layout-fixed text-sm">
<div class="wrapper">
    <div class="modal fade in show" id="loader" style="padding-left: 17px;z-index: 99999;">
        <div class="modal-dialog modal-xs" style="position: absolute;left: 50%;top: 15%">
                    <img width="50%" src="{{ asset('dist/img/loader.gif') }}">
            </div>
        </div>
  @include('includes.nav')
  <!-- Main Sidebar Container -->
  @include('includes.sidebar')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    @yield('content')
  </div>
  <!-- /.content-wrapper -->
  @include('layout.footer')

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
@include('layout.script')
</body>
</html>
