@extends('layouts.app')

@section('content')
  <div class="card-body">
    <div class="auth-logo">
      <h3 class="text-center">
        <a href="javascript:;" class="logo d-block my-4">
          <img src="{{ asset('backend/assets/images/logo-dark.png') }}" class="logo-dark mx-auto" height="80" alt="logo-dark">
          <img src="{{ asset('backend/assets/images/logo-light.png') }}" class="logo-light mx-auto" height="80" alt="logo-light">
        </a>
      </h3>
    </div>

    <div class="p-3">
      <h4 class="text-muted font-size-18 text-center">¡Bienvenido!</h4>
      <p class="text-muted text-center">Inicie sesión para continuar.</p>
      <form class="form-horizontal" method="POST" action="{{ route('login') }}">
        @csrf
        <div class="mb-3">
          <label class="form-label" for="email">{{ __('Correo Electrónico') }}</label>
          <input type="email" class="form-control" id="email" placeholder="Ingrese correo" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
          @error('email')
            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
          @enderror
        </div>

        <div class="mb-3">
          <label class="form-label" for="userpassword">{{ __('Contraseña') }}</label>
          <input type="password" class="form-control" id="userpassword" placeholder="Ingrese su contraseña" name="password" required autocomplete="current-password">
          @error('password')
            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
          @enderror
        </div>

        <div class="mb-3 row">
          <div class="col-6">
            <div class="form-check">
              <input type="checkbox" class="form-check-input cursor-pointer" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
              <label class="form-check-label cursor-pointer select-none" for="remember">Recordar</label>
            </div>
          </div>
          <div class="col-6 text-end">
            <button class="btn btn-primary w-md waves-effect waves-light" type="submit"><i class="ion ion-ios-send"></i> Ingresar</button>
          </div>
        </div>
      </form>
    </div>

  </div>
@endsection

{{--
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body>
      <div class="flex-center position-ref full-height">
        @if (Route::has('login'))
          <div class="top-right links">
            @auth
              <a href="{{ url('/home') }}">Dashboard</a>
            @else
              <a href="{{ route('login') }}">Iniciar sesión</a>
              @if (Route::has('register'))
                <a href="{{ route('register') }}">Registrar</a>
              @endif
            @endauth
          </div>
        @endif
      </div>
    </body>
</html> --}}
