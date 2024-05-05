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
                            <h5 class="fw-bold">ESTADOS DE EXPIRACIÓN EN LICENCIAS MYCOMPANY</h5>
                        </div>
                        <div class="col-sm-12 text-center">
                            <div class="color-description bg-toinstall">
                                <p> <i class="far fa-square icon-color-toinstall"></i>NUEVO</p>
                            </div>
                            <div class="color-description bg-installed">
                                <p> <i class="fas fa-square icon-color-installed"></i>PRODUCT </p>
                            </div>
                            <div class="color-description bg-byExpire">
                                <p> <i class="fas fa-square icon-color-byExpire"></i>PRUEBA</p>
                            </div>
                            <div class="color-description bg-expired">
                                <p> <i class="fas fa-square icon-color-expired"></i>EXPIRADO</p>
                            </div>
                        </div>
                        <hr>
                        @livewire('my-company')
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
