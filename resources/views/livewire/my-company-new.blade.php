<div>
    <div class="row">
        <div class="mb-3 col-6 col-md-6 col-lg-3">
            <label class="form-label">Nro de RUC</label>
            <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected">
                <input wire:model='ruc' type="text" placeholder="N° de RUC" class="form-control">
                <span class="input-group-btn input-group-append">
                    <button type="button" class="btn btn-primary bootstrap-touchspin-up">
                        <i class="fas fa-search"></i>
                    </button>
                </span>
            </div>
        </div>
        <div class="mb-3 col-6 col-md-6 col-lg-3">
            <label for="RazonSocial" class="form-label">Razon Social<span>*</span></label>
            <input wire:model='RazonSocial' type="text" id="RazonSocial" placeholder="Complete razon social."
                autocomplete="off" class="form-control">
        </div>
        <div class="mb-3 col-6 col-md-6 col-lg-3">
            <label for="plan" class="form-label">Plan MyCompany<span>*</span></label>
            <select wire:model='plan' id="plan" class="form-select">
                <option value=""> __Seleccione__ </option>
                <option value="Mensual">Mensual</option>
                <option value="Anual">Anual</option>
            </select>
        </div>
        <div class="mb-3 col-12 col-md-6 col-lg-3">
            <label for="monto" class="form-label">Monto Pagado<span>*</span></label>
            <input wire:model='monto' type="text" id="monto" autocomplete="off" class="form-control">
        </div> <!---->
    </div>
    <div class="row">
        <div class="mb-3 col-12 col-md-6 col-lg-4">
            <label for="fecha_suscription" class="form-label">{{ $fecha_suscription}}<span>*</span></label>
            <input wire:model='fecha_suscription' type="date" id="fecha_suscription" autocomplete="off"
                class="form-control">
        </div> <!---->
        <div class="mb-3 col-12 col-md-6 col-lg-4">
            <label for="fecha_certificacion" class="form-label">Fecha Certificado<span>*</span></label>
            <input wire:model='fecha_certificacion' type="date" id="fecha_certificacion" autocomplete="off"
                class="form-control">
        </div> <!---->
    </div>
    <div class="row">
        <div class="mb-3 col-12 col-md-6 col-lg-4">
            <label for="fin_suscription" class="form-label">Fin Suscripcion<span>*</span></label>
            <input wire:model='fin_suscription' type="date" id="fin_suscription" autocomplete="off"
                class="form-control">
        </div> <!---->
        <div class="mb-3 col-12 col-md-6 col-lg-4">
            <label for="fin_certificacion" class="form-label">Fin Certificado<span>*</span></label>
            <input wire:model='fin_certificacion' type="date" id="fin_certificacion" autocomplete="off"
                class="form-control">
        </div>
    </div>
    <div class="row">
        <div class="mb-3 col-12 col-md-6 col-lg-8">
            <label for="nota" class="form-label">Nota</label>
            <textarea wire:model='nota' rows="3" placeholder="Ingrese nota" id="nota" autocomplete="off"
                class="form-control"></textarea>
        </div>
    </div>
    <div class="row">
        <div class="mb-3 col-6 col-md-6 col-lg-3">
            <label for="estado" class="form-label">Estado<span>*</span></label>
            <select wire:model='estado' id="estado" class="form-select">
                <option value=""> __Seleccione__ </option>
                <option value="bg-toinstall">NUEVO</option>
                <option value="bg-installed">PRODUCT</option>
                <option value="bg-byExpire">PRUEBA</option>
                <option value="bg-expired">EXPIRADO</option>
            </select>
        </div>
    </div>
    <div class="row">
        <div class="mb-3 col-12 col-md-6 col-lg-4">
            <button class="btn btn-danger">Cancelar</button>
            <button wire:click='{{ $mc ? 'update' : 'save' }}' class="btn btn-primary">Guardar</button>
        </div>
    </div>
</div>
