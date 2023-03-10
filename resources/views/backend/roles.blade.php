@extends('backend.layout.app')

@section('title')
  Roles
@endsection

@section('header-title')
  <div class="row">
    <div class="col-12">
      <div class="page-title-box">
        <div class="page-title">
          <h4 class="mb-0 font-size-18">Lista de roles</h4>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript: void(0);">Full tecnología</a></li>
            <li class="breadcrumb-item"><a href="javascript: void(0);">Administrar</a></li>
            <li class="breadcrumb-item active select-none">Roles</li>
          </ol>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('content')
  <div class="page-content-wrapper">
    {{-- can-view="{{ auth()->user()->can('view users') }}"
    can-create="{{ auth()->user()->can('create users') }}"
    can-edit="{{ auth()->user()->can('edit users') }}"
    can-update-status="{{ auth()->user()->can('update status users') }}" --}}
    {{-- <contact-component></contact-component> --}}
    <role-component
      can-view="{{ auth()->user()->can('view roles') }}"
      can-edit="{{ auth()->user()->can('update roles') }}"
      is-admin="{{ auth()->user()->hasRole('administrador') }}"
    ></role-component>
  </div>
@endsection
