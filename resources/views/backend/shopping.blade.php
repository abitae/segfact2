@extends('backend.layout.app')

@section('title')
  Gestión de compras
@endsection

@section('header-title')
  <div class="row">
    <div class="col-12">
      <div class="page-title-box">
        <div class="page-title">
          <h4 class="mb-0 font-size-18">Lista de Compras</h4>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript: void(0);">Full tecnología</a></li>
            <li class="breadcrumb-item"><a href="javascript: void(0);">Administrar</a></li>
            <li class="breadcrumb-item active select-none">Compras</li>
          </ol>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('content')
  <div class="page-content-wrapper">
    <shopping-component
      can-view="{{ auth()->user()->can('view pucharses') }}"
      can-create="{{ auth()->user()->can('create pucharse') }}"
      can-edit="{{ auth()->user()->can('edit pucharse') }}"
      can-update-status="{{ auth()->user()->can('update status pucharse') }}"
      can-print-report="{{ auth()->user()->can('print report pucharse') }}"
    ></shopping-component>
  </div>
@endsection
