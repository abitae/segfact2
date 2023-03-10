@extends('backend.layout.app')

@section('title')
  Empresas
@endsection

@section('header-title')
  <div class="row">
    <div class="col-12">
      <div class="page-title-box">
        <div class="page-title">
          <h4 class="mb-0 font-size-18">Lista de Empresas</h4>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript: void(0);">Full tecnología</a></li>
            <li class="breadcrumb-item"><a href="javascript: void(0);">Administrar</a></li>
            <li class="breadcrumb-item active select-none">Empresas</li>
          </ol>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('content')
  <div class="page-content-wrapper">
    <enterprise-component
      can-view="{{ auth()->user()->can('view enterprises') }}"
      can-create="{{ auth()->user()->can('create enterprise') }}"
      can-edit="{{ auth()->user()->can('edit enterprise') }}"
      can-update-status="{{ auth()->user()->can('update status enterprise') }}"
    ></enterprise-component>
  </div>
@endsection
