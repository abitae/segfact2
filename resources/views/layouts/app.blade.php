<!doctype html>
<html lang="es">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Sistema') }}</title>
    <meta name="end-point" content="http://quotationtracking.test/">
    <script src="{{ asset('js/app.js') }}" defer></script>
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css" integrity="sha512-YWzhKL2whUzgiheMoBFwW8CKV4qpHQAEuvilg9FAn5VJUDwKZZxkJNuGM4XkWuk94WCrrwslk8yWNGmY1EduTA==" crossorigin="anonymous" referrerpolicy="no-referrer" /> --}}
    <meta name="theme-color" content="#1B82EC">
    <link rel="shortcut icon" href="{{ asset('backend/assets/images/favicon.ico') }}">
    <link href="{{ asset('backend/assets/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <link href="{{ asset('backend/assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('backend/assets/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css"/>
    <style>
      .cursor-pointer {
        cursor: pointer;
      }
      .select-none {
        user-select: none;
      }
    </style>
  </head>
  <body data-topbar="colored">
    <div class="account-pages"></div>
      <div class="wrapper-page" id="app">
        <div class="card">
            @yield('content')
        </div>

        <div class="text-center">
          {{-- <p class="text-white-50">Don't have an account ? <a href="javascript:;" class="text-white"> Signup Now </a> </p> --}}
          <p class="text-muted"> © {{ \Carbon\Carbon::now()->format('Y')}} Full Tecnología</p>
        </div>

      </div>

      <div class="rightbar-overlay"></div>
      <script src="{{ asset('backend/assets/libs/jquery/jquery.min.js') }}"></script>
      <script src="{{ asset('backend/assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
      <script src="{{ asset('backend/assets/libs/metismenu/metisMenu.min.js') }}"></script>
      <script src="{{ asset('backend/assets/libs/simplebar/simplebar.min.js') }}"></script>
      <script src="{{ asset('backend/assets/libs/node-waves/waves.min.js') }}"></script>
      <script src="{{ asset('backend/assets/libs/jquery-sparkline/jquery.sparkline.min.js') }}"></script>
      <script src="{{ asset('backend/assets/js/app.js') }}"></script>

  </body>
</html>
