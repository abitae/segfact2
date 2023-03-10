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
