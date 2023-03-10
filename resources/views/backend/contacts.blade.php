@extends('backend.layout.app')

@section('title')
  Contactos
@endsection

@section('header-title')
  <div class="row">
    <div class="col-12">
      <div class="page-title-box">
        <div class="page-title">
          <h4 class="mb-0 font-size-18">Lista de contactos</h4>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript: void(0);">Full tecnología</a></li>
            <li class="breadcrumb-item"><a href="javascript: void(0);">Administrar</a></li>
            <li class="breadcrumb-item active select-none">Contactos</li>
          </ol>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('content')
  <div class="page-content-wrapper">
    <contact-component
      can-view="{{ auth()->user()->can('view contacts') }}"
      can-create="{{ auth()->user()->can('create contact') }}"
      can-edit="{{ auth()->user()->can('edit contact') }}"
      can-update-status="{{ auth()->user()->can('update status contact') }}"
    ></contact-component>
  </div>
@endsection
