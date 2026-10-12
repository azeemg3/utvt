<footer class="main-footer">
    <strong>Copyright &copy; {{ date('Y') }}
        <a href="{{ url('/') }}">{{ optional($site_setting)->business_name ?? config('app.name') }}</a>.</strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> 3.0.4
    </div>
  </footer>
