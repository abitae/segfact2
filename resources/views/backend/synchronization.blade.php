@extends('backend.layout.app')

@section('title')
  Sincronización
@endsection

@section('header-title')
  <div class="row">
    <div class="col-12">
      <div class="page-title-box">
        <div class="page-title">
          <h4 class="mb-0 font-size-18">Sincronización</h4>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript: void(0);">Full tecnología</a></li>
            <li class="breadcrumb-item"><a href="javascript: void(0);">Administrar</a></li>
            <li class="breadcrumb-item active select-none">Sincronización</li>
          </ol>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('content')
  <div class="page-content-wrapper">
    <synchronization-component
      can-create-shopping="{{ auth()->user()->can('create pucharse') }}"
      can-create-sale="{{ auth()->user()->can('create sale') }}"
    ></synchronization-component>
  </div>
@endsection
