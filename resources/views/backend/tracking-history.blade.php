@extends('backend.layout.app')

@section('title')
  Historial
@endsection

@section('header-title')
  <div class="row">
    <div class="col-12">
      <div class="page-title-box">
        <div class="page-title">
          <h4 class="mb-0 font-size-18">Historial</h4>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript: void(0);">Full tecnología</a></li>
            <li class="breadcrumb-item"><a href="javascript: void(0);">Administrar</a></li>
            <li class="breadcrumb-item active select-none">Historial</li>
          </ol>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('content')
  <div class="page-content-wrapper">
    <div class="row">
      <div class="col-sm-12 col-md-12">
        <div class="card">
          <div class="card-body">
            <div class="card-title title-with-button align-items-center">
              <h5 class="color-customized mb-0">Historial del comprobante {{$nroComprobante}}</h5>
              <a href="{{ url()->previous() }}" class="btn btn-danger"><i class="fas fa-angle-left"></i> Atrás</a>
            </div>
            <div class="col-12 col-sm-12">
              <div class="table-responsive">
                <table class="table table-sm">
                  <thead>
                    <tr>
                      <th>Responsable</th>
                      <th>Comprobante</th>
                      <th>Estado</th>
                      <th>Observación</th>
                      <th>Comprobante Refact</th>
                      <th class="text-center">Fecha - Hora</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($listHistory as $history)
                      <tr>
                        <th> {{$history->user->name ?? '-'}}</th>
                        <th class="y-center">{{ $history->nroComprobante }}</th>
                        <td class="y-center">{{ $history->estado->nombre }}</td>
                        <td class="y-center" style="min-inline-size: 200px;">{{ $history->observation }}</td>
                        <td class="y-center">{{ $history->refactoringCode ?? '-' }}</td>
                        <td class="xy-center" style="min-inline-size: 180px;">
                          {{ \Carbon\Carbon::parse($history->created_at)->formatLocalized('%d %B %Y') }}
                          {{ \Carbon\Carbon::parse($history->created_at)->isoFormat('h:mm a') }}
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    {{-- <user-component
      can-view="{{ auth()->user()->can('Ver usuarios') }}"
      can-create="{{ auth()->user()->can('Crear usuarios') }}"
      can-edit="{{ auth()->user()->can('Editar usuarios') }}"
      can-update-status="{{ auth()->user()->can('Actualizar estado de usuarios') }}"
    ></user-component> --}}
  </div>
@endsection
