@extends('backend.layout.app')

@section('title')
  Seguimiento
@endsection

@section('header-title')
  <div class="row">
    <div class="col-12">
      <div class="page-title-box">
        <div class="page-title">
          <h4 class="mb-0 font-size-18">Lista de comprobantes</h4>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript: void(0);">Full tecnología</a></li>
            <li class="breadcrumb-item"><a href="javascript: void(0);">Administrar</a></li>
            <li class="breadcrumb-item active select-none">Comprobantes</li>
          </ol>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('content')
  <div class="page-content-wrapper">
    <seguimiento-comprobante-component
      can-view="{{ auth()->user()->can('view invoice tracking') }}"
      can-create="{{ auth()->user()->can('create invoice tracking') }}"
      can-edit="{{ auth()->user()->can('edit invoice tracking') }}"
      can-update-status="{{ auth()->user()->can('update status invoice tracking') }}"
      can-print-report="{{ auth()->user()->can('print report invoice tracking') }}"
      is-admin="{{ auth()->user()->hasRole('administrador') }}"
    ></seguimiento-comproban-component>
  </div>
@endsection
