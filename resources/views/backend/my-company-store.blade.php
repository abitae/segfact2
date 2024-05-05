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
        <div class="row">
            <div class="col-12 col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="col-12 text-center">
                            <h5 class="fw-bold">{{ $mc ? 'EDITAR' : 'NUEVA' }} LICENCIA MYCOMPANY</h5>
                        </div>
                        <div class="col-sm-12 text-center">
                            @livewire('my-company-new', ['mc' => $mc])
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
