@extends('backend.layout.app')

@section('title')
  Inicio
@endsection

@section('header-title')
  <div class="row">
    <div class="col-12">
      <div class="page-title-box d-flex align-items-center justify-content-between">
        <div class="page-title select-none">
          <h4 class="mb-0 font-size-18">Inicio</h4>
          <ol class="breadcrumb">
            <li class="breadcrumb-item active">
              Bienvenido a <b>Full Tecnología</b>
            </li>
          </ol>
        </div>

        {{-- <div class="state-information d-none d-sm-block">
          <div class="state-graph">
            <div id="header-chart-1"></div>
            <div class="info">Balance S/ 2,317</div>
          </div>
          <div class="state-graph">
            <div id="header-chart-2"></div>
            <div class="info">Artículo vendido 1230</div>
          </div>
        </div> --}}
      </div>
    </div>
  </div>
@endsection

@section('scripts')
  <script src="{{ asset('backend/assets/js/pages/dashboard.init.js') }}"></script>
@endsection

@section('contents')
  <div class="page-content-wrapper">
    <div class="row">
      <div class="col-xl-3 col-md-6">
        <div class="card bg-primary mini-stat position-relative">
          <div class="card-body">
            <div class="mini-stat-desc">
              <h5 class="text-uppercase verti-label font-size-16 text-white-50">
                Pedidos
              </h5>
              <div class="text-white">
                <h5 class="text-uppercase font-size-16 text-white-50">
                  Pedidos
                </h5>
                <h3 class="mb-3 text-white">1,587</h3>
                <div class="">
                  <span class="badge bg-light text-info"> +11% </span>
                  <span class="ms-2">Del periodo anterior</span>
                </div>
              </div>
              <div class="mini-stat-icon">
                <i class="mdi mdi-cube-outline display-2"></i>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-xl-3 col-md-6">
        <div class="card bg-primary mini-stat position-relative">
          <div class="card-body">
            <div class="mini-stat-desc">
              <h5 class="text-uppercase verti-label font-size-16 text-white-50">
                Ingresos
              </h5>
              <div class="text-white">
                <h5 class="text-uppercase font-size-16 text-white-50">
                  Ingresos
                </h5>
                <h3 class="mb-3 text-white">S/ 46,785</h3>
                <div class="">
                  <span class="badge bg-light text-danger">
                    -29%
                  </span>
                  <span class="ms-2">Del periodo anterior</span>
                </div>
              </div>
              <div class="mini-stat-icon">
                <i class="mdi mdi-buffer display-2"></i>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-xl-3 col-md-6">
        <div class="card bg-primary mini-stat position-relative">
          <div class="card-body">
            <div class="mini-stat-desc">
              <h5 class="text-uppercase verti-label font-size-16 text-white-50">
                Pro. precio
              </h5>
              <div class="text-white">
                <h5 class="text-uppercase font-size-16 text-white-50">
                  Promedio precio
                </h5>
                <h3 class="mb-3 text-white">15.9</h3>
                <div class="">
                  <span class="badge bg-light text-primary">
                    0%
                  </span>
                  <span class="ms-2">Del periodo anterior</span>
                </div>
              </div>
              <div class="mini-stat-icon">
                <i class="mdi mdi-tag-text-outline display-2"></i>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-xl-3 col-md-6">
        <div class="card bg-primary mini-stat position-relative">
          <div class="card-body">
            <div class="mini-stat-desc">
              <h5 class="text-uppercase verti-label font-size-16 text-white-50">
                Productos
              </h5>
              <div class="text-white">
                <h5 class="text-uppercase font-size-16 text-white-50">
                  Productos
                </h5>
                <h3 class="mb-3 text-white">1890</h3>
                <div class="">
                  <span class="badge bg-light text-info"> +89% </span>
                  <span class="ms-2">Del periodo anterior</span>
                </div>
              </div>
              <div class="mini-stat-icon">
                <i class="mdi mdi-briefcase-check display-2"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
