@extends('backend.layout.app')

@section('title')
  Reporte
@endsection

@section('header-title')
  <div class="row">
    <div class="col-12">
      <div class="page-title-box">
        <div class="page-title">
          <h4 class="mb-0 font-size-18">Reporte</h4>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript: void(0);">Full tecnología</a></li>
            <li class="breadcrumb-item"><a href="javascript: void(0);">Administrar</a></li>
            <li class="breadcrumb-item active select-none">Reporte</li>
          </ol>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('content')
  <div class="page-content-wrapper">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <div class="card-title">
              <h5 class="text-primary select-none"><i class="far fa-file-alt"></i> Reporte</h5>
            </div>
            <div class="row">
              <div class="col-12 col-sm-12 col-md-3 col-lg-3 col-xl-2">
                <a href="javascript:;" class="btn btn-sm btn-warning d-block">Reporte Compras</a>
              </div>
              <div class="col-12 col-sm-12 col-md-3 col-lg-3 col-xl-2">
                <a href="javascript:;" class="btn btn-sm btn-primary d-block">Reporte Ventas</a>
              </div>
              <div class="col-12 col-sm-12 col-md-3 col-lg-3 col-xl-2">
                <a href="javascript:;" class="btn btn-sm btn-success d-block">Reporte Seguimiento</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
