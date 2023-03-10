@extends('backend.layout.app')

@section('title')
  Proveedores
@endsection

@section('header-title')
  <div class="row">
    <div class="col-12">
      <div class="page-title-box">
        <div class="page-title">
          <h4 class="mb-0 font-size-18">Lista de proveedores</h4>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript: void(0);">Full tecnología</a></li>
            <li class="breadcrumb-item"><a href="javascript: void(0);">Administrar</a></li>
            <li class="breadcrumb-item active select-none">Proveedores</li>
          </ol>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('content')
  <div class="page-content-wrapper">
    <supplier-component
      can-view="{{ auth()->user()->can('view suppliers') }}"
      can-create="{{ auth()->user()->can('create supplier') }}"
      can-edit="{{ auth()->user()->can('edit supplier') }}"
      can-update-status="{{ auth()->user()->can('update status supplier') }}"
    ></supplier-component>
  </div>
@endsection
