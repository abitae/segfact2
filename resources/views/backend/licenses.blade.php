@extends('backend.layout.app')

@section('title')
  Seg. Licencias
@endsection

@section('header-title')
  <div class="row">
    <div class="col-12">
      <div class="page-title-box">
        <div class="page-title">
          <h4 class="mb-0 font-size-18">Seguimiento licencias</h4>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript: void(0);">Full tecnología</a></li>
            <li class="breadcrumb-item"><a href="javascript: void(0);">Administrar</a></li>
            <li class="breadcrumb-item active select-none">Licencias</li>
          </ol>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('content')
  <div class="page-content-wrapper">
    <license-component
      can-view="{{ auth()->user()->can('view licenses') }}"
      can-create="{{ auth()->user()->can('create license') }}"
      can-edit="{{ auth()->user()->can('edit license') }}"
      can-update-status="{{ auth()->user()->can('update status license') }}"
    ></license-component>
  </div>
@endsection
