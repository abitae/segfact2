<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <title>@yield('title') | {{ config('app.name') }} </title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1" />
  <meta name="end-point" content="{{ config('app.end_point_template') }}">
  <link rel="shortcut icon" href="{{ asset('backend/assets/images/favicon.ico') }}">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="theme-color" content="#1B82EC">
  <meta name="authenticated-user" content="{{ auth()->user() }}"/>
  <link href="{{ asset('backend/assets/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
  <link href="{{ asset('backend/assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
  <link href="{{ asset('backend/assets/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
  <link href="{{ asset('backend/assets/css/styles-customized.css') }}" rel="stylesheet" type="text/css" />
  @yield('styles')
  <script src="{{ asset('js/app.js') }}" defer></script>
  @livewireStyles
</head>

<body data-topbar="colored">
  <div id="layout-wrapper">
    <header id="page-topbar">
      <div class="navbar-header">
        <div class="d-flex">

          <div class="navbar-brand-box">
            <a href="{{ route('dashboard') }}" class="logo logo-dark">
              <span class="logo-sm">
                <img src="{{ asset('backend/assets/images/logo-sm-dark.png') }}" alt="Icono de Full Tecnología" height="25"/>
              </span>
              <span class="logo-lg">
                <img src="{{ asset('backend/assets/images/logo-dark.png') }}" alt="Logo de Full Tecnología" height="60"/>
              </span>
            </a>

            <a href="{{ route('dashboard') }}" class="logo logo-light">
              <span class="logo-sm">
                <img src="{{ asset('backend/assets/images/logo-sm-light.png') }}" alt="Icono de Full Tecnología" height="25" />
              </span>
              <span class="logo-lg">
                <img src="{{ asset('backend/assets/images/logo-light.png') }}" alt="Logo de Full Tecnología" height="60" />
              </span>
            </a>
          </div>

          <button type="button" class="btn px-3 font-size-24 header-item waves-effect" id="vertical-menu-btn">
            <i class="mdi mdi-menu"></i>
          </button>
        </div>

        <div class="d-flex">
          <div class="dropdown d-inline-block d-lg-none ms-2">
            <button type="button" class="btn header-item noti-icon waves-effect" id="page-header-search-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="mdi mdi-magnify"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
              aria-labelledby="page-header-search-dropdown">
              <form class="p-3">
                <div class="form-group m-0">
                  <div class="input-group">
                    <input type="search" class="form-control" placeholder="Buscar..."
                      aria-label="Recipient's username" />
                    <div class="input-group-append">
                      <button class="btn btn-primary" type="submit">
                        <i class="mdi mdi-magnify"></i>
                      </button>
                    </div>
                  </div>
                </div>
              </form>
            </div>
          </div>

          <div class="dropdown d-inline-block">
            <button type="button" class="btn btn-settings-options header-item waves-effect" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <span class="pe-1"><i class="fas fa-angle-down pe-1"></i> {{ auth()->user()->nickName }}</span>
              <img class="rounded-circle header-profile-user" src="{{ asset('backend/assets/images/stickers/avatar.png') }}" alt="Avatar usuario" />
            </button>

            <div class="dropdown-menu dropdown-menu-end">
              <a class="dropdown-item" href="{{ route('profile') }}"><i class="mdi mdi-account-circle font-size-16 align-middle me-2 text-muted"></i><span>Mí perfil</span></a>
              <div class="dropdown-divider"></div>

              <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
              </form>

              <a class="dropdown-item text-primary"
                href="{{ route('logout') }}"
                onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                <i class="mdi mdi-power font-size-16 align-middle me-2 text-primary"></i>
                <span>Cerrar sesión</span>
              </a>
            </div>
          </div>

          <div class="dropdown d-inline-block">
            <button type="button" class="btn btn-icon-theme header-item noti-icon right-bar-toggle waves-effect">
              <i class="fas fa-adjust"></i>
            </button>
          </div>
        </div>
      </div>
    </header>

    <div class="vertical-menu">
      <div data-simplebar class="h-100">
        @include('backend.layout.sidebar')
      </div>
    </div>


    <div class="main-content">
      <div class="page-content">
        <div id="app" class="container-fluid">
          @yield('header-title')
          @yield('content')
        </div>
      </div>

      <footer class="footer">
        <div class="container-fluid">
          <div class="row">
            <div class="col-sm-12 text-center select-none">
              {{ \Carbon\Carbon::now()->format('Y') }} &copy; Full tecnología
              <span class="d-none d-sm-inline-block"> - Todos los derechos reservados</span>
            </div>
          </div>
        </div>
      </footer>
    </div>
  </div>

  <div class="right-bar">
    <div data-simplebar class="h-100">
      <div class="rightbar-title px-3 py-4">
        <a href="javascript:void(0);" class="right-bar-toggle float-end">
          <i class="mdi mdi-close noti-icon"></i>
        </a>
        <h5 class="m-0">Configuración de tema</h5>
      </div>

      <hr class=""/>
      <h6 class="text-center mb-0">Modo de pantalla</h6>

      <div class="p-4">
        <div class="mb-2">
          <label class="cursor-pointer" for="light-mode-switch">
            <img src="{{ asset('backend/assets/images/layouts/layout-1.png') }}" class="img-fluid img-thumbnail" alt="" />
          </label>
        </div>

        <div class="form-check form-switch mb-3">
          <input type="radio" class="form-check-input theme-choice cursor-pointer" id="light-mode-switch" checked />
          <label class="form-check-label select-none cursor-pointer" for="light-mode-switch">Modo claro</label>
        </div>

        <div class="mb-2">
          <label class="cursor-pointer" for="dark-mode-switch">
            <img src="{{ asset('backend/assets/images/layouts/layout-2.png') }}" class="img-fluid img-thumbnail" alt="" />
          </label>
        </div>

        <div class="form-check form-switch mb-3">
          <input type="radio" class="form-check-input theme-choice cursor-pointer" id="dark-mode-switch"
            data-bsStyle="assets/css/bootstrap-dark.min.css" data-appStyle="assets/css/app-dark.min.css" />
          <label class="form-check-label select-none cursor-pointer" for="dark-mode-switch">Modo oscuro</label>
        </div>
      </div>
    </div>
  </div>

  <div class="rightbar-overlay"></div>
  <script src="{{ asset('backend/assets/libs/jquery/jquery.min.js') }}"></script>
  <script src="{{ asset('backend/assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('backend/assets/libs/metismenu/metisMenu.min.js') }}"></script>
  <script src="{{ asset('backend/assets/libs/simplebar/simplebar.min.js') }}"></script>
  <script src="{{ asset('backend/assets/libs/node-waves/waves.min.js') }}"></script>
  <script src="{{ asset('backend/assets/libs/jquery-sparkline/jquery.sparkline.min.js') }}"></script>

  <script src="{{ asset('backend/assets/libs/peity/jquery.peity.min.js') }}"></script>
  <script src="{{ asset('backend/assets/libs/morris.js/morris.min.js') }}"></script>
  <script src="{{ asset('backend/assets/libs/raphael/raphael.min.js') }}"></script>
  <script src="{{ asset('backend/assets/js/app.js') }}"></script>
  @yield('scripts')
  @livewireScripts
</body>
</html>
