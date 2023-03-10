@extends('backend.layout.app')

@section('title')
  Gestión de ventas
@endsection

@section('header-title')
  <div class="row">
    <div class="col-12">
      <div class="page-title-box">
        <div class="page-title">
          <h4 class="mb-0 font-size-18">Lista de Ventas</h4>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript: void(0);">Full tecnología</a></li>
            <li class="breadcrumb-item"><a href="javascript: void(0);">Administrar</a></li>
            <li class="breadcrumb-item active select-none">Ventas</li>
          </ol>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('content')
  <div class="page-content-wrapper">
    <sale-component
      can-view="{{ auth()->user()->can('view sales') }}"
      can-create="{{ auth()->user()->can('create sale') }}"
      can-edit="{{ auth()->user()->can('edit sale') }}"
      can-update-status="{{ auth()->user()->can('update status de sale') }}"
      can-print-report="{{ auth()->user()->can('print report de sale') }}"
      is-admin="{{ auth()->user()->hasRole('administrador') }}"
    ></sale-component>
  </div>
@endsection
