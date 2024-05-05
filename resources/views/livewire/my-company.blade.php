<div>
    <div style="min-height: 300px;">
        <div class="card-title select-none title-with-button">
            <h5 class="color-customized"><i class="fas fa-search"></i> Filtros de búsqueda</h5>
            <a href="{{ route('newMyCompany') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Nuevo
            </a>
        </div>


        <div class="mb-3 col-12 col-md-6 col-lg-3 col-xl-2">
            <label class="form-label" for="nameClientSearch">Buscar Cliente <i class="fas fa-question-circle"
                    title="Seleccione una opción para buscar"></i></label>
            <input wire:model='search' type="text" class="form-control" placeholder="Buscar...">
        </div>

        <table class="table table-sm">
            <thead>
                <tr>
                    <th class="column-permission">Action</th>
                    <th class="column-permission">RUC</th>
                    <th class="column-permission">Razon Social</th>
                    <th class="column-permission">Plan</th>
                    <th class="column-permission">Ini Suscripcion</th>
                    <th class="column-permission">Ini Certificado</th>
                    <th class="column-permission">Fin Suscripcion</th>
                    <th class="column-permission">Fin Certificado</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($myCompany as $mc)
                    <tr class="{{ $mc->estado }}">
                        <td class="text-capitalize column-permission">
                            <a href="{{ route('updateMyCompany', $mc->id) }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-tasks"></i>
                                Editar
                            </a>
                        </td>
                        <td class="text-left">
                            {{ $mc->ruc }}
                        </td>
                        <td class="text-left">
                            {{ $mc->RazonSocial }}
                        </td>
                        <td class="text-left">
                            {{ $mc->plan }}
                        </td>
                        <td class="text-left">
                            {{ \Carbon\Carbon::parse($mc->fecha_suscription)->format('d/m/Y') }}
                        </td>
                        <td class="text-left">
                            {{ \Carbon\Carbon::parse($mc->fecha_certificacion)->format('d/m/Y') }}
                        </td>
                        <td class="table-light">                       
                                {{ \Carbon\Carbon::parse($mc->fin_suscription)->format('d/m/Y') }}              
                        </td>
                        <td class="text-left">
                            {{ \Carbon\Carbon::parse($mc->fin_certificacion)->format('d/m/Y') }}
                        </td>
                    </tr>
                @empty
                @endforelse
            </tbody>
        </table>
        {{ $myCompany->links() }}
    </div>
</div>
