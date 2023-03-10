<template>
  <div>
    <LoaderComponent v-if="isLoading"></LoaderComponent>
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <div style="min-height: 300px;">
              <div class="card-title select-none title-with-button">
                <h5 class="color-customized"><i class="fas fa-search"></i> Filtros de búsqueda</h5>
                <!-- <button class="btn btn-primary" @click="openModalCreateShopping">
                  <i class="fas fa-plus"></i> Nuevo
                </button> -->
                <a
                  v-if="can(canCreate)"
                  :href="`${END_POINT}synchronization`" class="btn btn-primary">
                  <i class="fas fa-sync"></i> Sincronización
                </a>
              </div>
              <div
                v-if="can(canPrintReport)"
                class="row">
                <div class="col-12 mb-3">
                  <button title="Exportación en Excel" @click="downloadExcel" class="btn btn-excel mb-1">Descargar Excel <i class="far fa-file-excel"></i></button>
                  <button title="Exportación en PDF" @click="downloadPdf" class="btn btn-pdf mb-1">Descargar Pdf <i class="far fa-file-pdf"></i></button>
                </div>
              </div>
              <form class="row" action="javascript:;">
                <div class="mb-3 col-12 col-md-6 col-lg-3 col-xl-2">
                  <label class="form-label" for="codigoFacturaSearch">N° Factura <i class="fas fa-question-circle" title="Presione la tecla ENTER para buscar"></i></label>
                  <input class="form-control text-uppercase" type="text"
                    id="codigoFacturaSearch"
                    name="codigoFacturaSearch"
                    v-model="codigoFacturaSearch"
                    @keyup.enter="getListDataShopping()"
                    placeholder="Escríbe aquí..."
                    autocomplete="off">
                </div>
                <div class="mb-3 col-12 col-md-6 col-lg-3 col-xl-2">
                  <label class="form-label" for="nroDocumentSupplierSearch">RUC/DNI Proveedor <i class="fas fa-question-circle" title="Presione la tecla ENTER para buscar"></i></label>
                  <input class="form-control"
                    type="text"
                    id="nroDocumentSupplierSearch"
                    name="nroDocumentSupplierSearch"
                    v-model="nroDocumentSupplierSearch"
                    @keyup.enter="getListDataShopping()"
                    placeholder="Escríbe aquí..."
                    autocomplete="off">
                </div>

                <div class="mb-3 col-12 col-md-6 col-lg-3 col-xl-2">
                  <label class="form-label" for="idTipoComprobanteSearch">Tipo de Comprobante <i class="fas fa-question-circle" title="Seleccione una opción para buscar"></i></label>
                  <select
                    id="idTipoComprobanteSearch"
                    name="idTipoComprobanteSearch"
                    v-model="idTipoComprobanteSearch"
                    @change="getListDataShopping()"
                    class="form-select">
                    <option value="" selected>Todos</option>
                    <option v-for="(typeDocument, index) in listTypeDocuments" :key="index" :value="typeDocument.id"> {{ typeDocument.nombre }} </option>
                  </select>
                </div>

                <div class="mb-3 col-12 col-md-6 col-lg-3 col-xl-2">
                  <label class="form-label" for="compraSolesDolaresSearch">Compra en Soles/Dólares <i class="fas fa-question-circle" title="Seleccione una opción para buscar"></i></label>
                  <select
                    id="compraSolesDolaresSearch"
                    v-model="compraSolesDolaresSearch"
                    @change="getListDataShopping()"
                    class="form-select">
                    <option value="" selected>Todos</option>
                    <option value="PEN">Soles</option>
                    <option value="USD">Dólares</option>
                  </select>
                </div>
                <div class="mb-3 col-12 col-md-6 col-lg-3 col-xl-2">
                  <label class="form-label" for="fechaEmisionSearch">Fecha Emisión <i class="fas fa-question-circle" title="Seleccione una fecha para buscar"></i></label>
                  <input
                    type="date"
                    class="form-control"
                    @change="getListDataShopping()"
                    id="fechaEmisionSearch"
                    v-model="fechaEmisionSearch">
                </div>
                <input class="d-none" type="submit">
              </form>

              <div v-if="listDataShopping.total && can(canView)" class="col-sm-12">
                <div class="table-responsive">
                  <table class="table table-sm table-hover table-striped table-bordered">
                    <thead class="thead-primary">
                      <tr>
                        <th class="options">Opciones</th>
                        <th class="status">Estado</th>
                        <th class="text-center">N° factura</th>
                        <th>Proveedor</th>
                        <th class="column-type-document">Tipo comprobante</th>
                        <th class="text-center">Soles/Dólares</th>
                        <th class="text-center">Tipo cambio</th>
                        <th class="text-center">Sub total</th>
                        <th class="text-center">Igv</th>
                        <th class="text-center">Monto total</th>
                        <th class="date-issue text-center">Fecha emisión</th>
                      </tr>
                    </thead>
                    <tbody class="vertical-align-middle">
                      <tr v-for="(shopping,index) in listDataShopping.data" :key="index">
                        <td class="options">
                          <button
                            class="btn btn-sm btn-primary"
                            @click="showDetails(shopping.detail, shopping.compraSolesDolares)"
                            title="Ver detalle">
                            <i class="far fa-eye"></i>
                          </button>
                          <!-- <button
                            @click="openModalEditShopping(shopping)"
                            class="btn btn-sm btn-primary"
                            >
                            <i class="far fa-edit"></i>
                          </button> -->
                          <button
                            v-if="can(canUpdateStatus)"
                            @click="updataStatus(shopping)"
                            :class="`btn btn-sm btn-${ shopping.is_active ? 'success' : 'danger' }`">
                            <i v-if="shopping.is_active" class="far fa-check-circle"></i>
                            <i v-else class="far fa-times-circle"></i>
                          </button>
                        </td>
                        <td class="status">
                          <span v-if="shopping.is_active" class="badge rounded-pill bg-success registration-status">Activo</span>
                          <span v-else class="badge rounded-pill bg-danger registration-status">Desactivado</span>
                        </td>
                        <td class="text-center cursor-default user-select-all">
                          <span class="cursor-pointer" title="Copiar" @click="copiedAtClipboard(shopping.codigoFactura)">{{ shopping.codigoFactura }}</span>
                        </td>
                        <td class="column-supplier">
                          <span class="select-all cursor-pointer" title="Copiar" @click="copiedAtClipboard(shopping.proveedor.nroDocument)">
                            {{ shopping.proveedor.nroDocument }}
                          </span> - {{ shopping.proveedor.fullName }}
                        </td>
                        <td> ({{ shopping.tipo_comprobante.codigo }}) {{ shopping.tipo_comprobante.nombre }} </td>
                        <td class="text-capitalize text-center"> {{ shopping.compraSolesDolares == 'PEN' ? 'Soles' : 'Dólares'  }} </td>
                        <td class="text-center"> {{ shopping.tipoDeCambio }} </td>
                        <td class="text-center"> S/ {{ shopping.montoVentaSoles }} </td>
                        <td class="text-center"> S/ {{ shopping.igv }} </td>
                        <td class="text-center"> S/ {{ shopping.montoTotal }} </td>
                        <td class="text-center"> {{ shopping.fechaEmision | formatDate }}</td>
                      </tr>
                    </tbody>
                  </table>
                  <pagination class="select-none"
                    :limit="5"
                    size="small"
                    :data="listDataShopping"
                    @pagination-change-page="getListDataShopping"
                  >
                    <span slot="prev-nav"><i class="fas fa-arrow-left"></i></span>
                    <span slot="next-nav"><i class="fas fa-arrow-right"></i></span>
                  </pagination>
                </div>
              </div>
              <template v-else>
                <EmptyComponent></EmptyComponent>
              </template>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="modal-shopping" tabindex="-1" aria-labelledby="Modal para registrar proveedores" aria-modal="true" role="dialog">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title select-none" id="Modal para registrar proveedores"> {{ modalTitle }} Compra </h5>
            <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close" @click="hideModal"></button>
          </div>
          <div class="modal-body">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active"
                      id="home-tab"
                      data-bs-toggle="tab"
                      href="#general"
                      role="tab"
                      aria-controls="home"
                      aria-selected="true">General</a>
                </li>
                <li v-if="Object.keys(supplier).length" class="nav-item" role="presentation">
                    <a class="nav-link"
                      id="profile-tab"
                      data-bs-toggle="tab"
                      href="#series"
                      role="tab"
                      aria-controls="profile"
                      aria-selected="false">Series</a>
                </li>
            </ul>
            <div class="tab-content p-3" id="myTabContent">
              <div class="tab-pane fade active show" id="general" role="tabpanel" aria-labelledby="home-tab">
                <div class="row">
                  <div class="col-12 col-sm-12 col-md-5 col-lg-4 mb-3">
                    <label class="form-label">N° Doc. Proveedor<span>*</span> </label>
                    <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected">
                      <input type="number" class="form-control" placeholder="00000000000" v-model="nroDocumentSupplier" @keyup.enter="searchSupplier(nroDocumentSupplier)">
                      <span class="input-group-btn input-group-append">
                        <button class="btn btn-primary bootstrap-touchspin-up" type="button" @click="searchSupplier(nroDocumentSupplier)">
                          <i  v-if="!isSearchSupplier" class="fas fa-search"></i>
                          <div v-else class="spinner-border text-white" role="status">
                            <span class="sr-only">Cargando...</span>
                          </div>
                        </button>
                      </span>
                    </div>
                  </div>
                  <template v-if="Object.keys(supplier).length">
                      <div class="col-12 col-sm-12 col-md-8 col-lg-8 mb-3">
                        <div class="information-supplier">
                          <button class="btn btn-sm btn-danger btn-remove-supplier" @click="removeSupplier"><i class="fas fa-times"></i></button>
                          <p class="mb-0">Nombre / Razón Social</p>
                          <p class="mb-0 fullNameSupplier">{{supplier.fullName}}</p>
                          <p class="mb-0">Dirección</p>
                          <p class="mb-0 fullNameSupplier">{{supplier.address}}</p>
                        </div>
                      </div>

                      <div class="col-12 mb-3">
                        <label class="form-label" for="idUsuario">Comprador</label>
                        <v-select
                          :options="listUsers"
                          :getOptionLabel="user => user.name"
                          v-model="idUser"
                          :reduce="user => user.id"
                          placeholder="Escribe nombre de vendedor"
                          >
                          <div slot="no-options">No hay resultados para mostrar.</div>
                        </v-select>
                      </div>

                      <div class="mb-3 col-12 col-sm-12 col-md-4 col-lg-4">
                        <label class="form-label" for="fechaEmision">Fecha de Emisión <span>*</span></label>
                        <input type="date" class="form-control" id="fechaEmision" :min="dateInitWorking" v-model="fechaEmision">
                      </div>

                      <div class="mb-3 col-12 col-sm-12 col-md-4 col-lg-4">
                        <label class="form-label" for="codigoFactura">N° de Factura <span>*</span></label>
                        <input type="text" class="form-control" id="codigoFactura" v-model="codigoFactura" placeholder="Ejem: F0000" autocomplete="off">
                      </div>

                      <div class="mb-3 col-12 col-sm-12 col-md-4 col-lg-4">
                        <label class="form-label" for="idTipoComprobante">Tipo de comprobante<span>*</span></label>
                        <select class="form-select" id="idTipoComprobante" v-model="idTipoComprobante">
                          <option value=""> __Seleccione__ </option>
                          <option v-for="(typeDocument, index) in listTypeDocuments" :key="index" :value="typeDocument.id"> {{ typeDocument.nombre }} </option>
                        </select>
                      </div>

                      <div class="mb-3 col-12 col-sm-12 col-md-4 col-lg-4">
                        <label class="form-label" for="compraSolesDolares">Compra en Soles/Dólares<span>*</span></label>
                        <select class="form-select" id="compraSolesDolares" v-model="compraSolesDolares" @change="compraSolesDolaresHandleChange">
                          <option value=""> __Seleccione__ </option>
                          <option value="soles"> Soles </option>
                          <option value="dolares"> Dólares </option>
                        </select>
                      </div>

                      <template v-if="compraSolesDolares === 'dolares'">
                        <div class="mb-3 col-12 col-sm-12 col-md-4 col-lg-4">
                          <label class="form-label" for="monto">Monto<span>*</span></label>
                          <input type="number" min="0" step="0.01" class="form-control" id="monto" v-model="monto" @blur="calculateOperationInDolares" placeholder="100.00">
                        </div>

                        <div class="mb-3 col-12 col-sm-12 col-md-4 col-lg-4">
                          <label class="form-label" for="tipoDeCambio">Tipo de cambio<span>*</span></label>
                          <input type="number" min="0" step="0.01" class="form-control" id="tipoDeCambio" v-model="tipoDeCambio" placeholder="0.000">
                        </div>
                      </template>

                      <template v-if="Boolean(compraSolesDolares)">
                        <div class="mb-3 col-12 col-sm-12 col-md-4 col-lg-4">
                          <label class="form-label" for="montoVentaSoles">Sub total<span>*</span></label>
                          <input type="number" min="0" step="0.01" class="form-control" id="montoVentaSoles" v-model="montoVentaSoles" @blur="calculateOperationInSoles"
                          :readonly="compraSolesDolares == 'dolares'"
                          placeholder="0.000">
                        </div>
                        <div class="mb-3 col-12 col-sm-12 col-md-4 col-lg-4">
                          <label class="form-label" for="igv">IGV</label>
                          <input type="number" min="0" step="0.01" class="form-control" readonly id="igv" v-model="igv" placeholder="0.00">
                        </div>
                        <div class="mb-3 col-12 col-sm-12 col-md-4 col-lg-4">
                          <label class="form-label" for="montoTotal">Monto total</label>
                          <input type="number" min="0" step="0.01" class="form-control" readonly id="montoTotal" v-model="montoTotal" placeholder="0.00">
                        </div>
                        <div class="mb-3 col-12">
                          <label class="form-label" for="descripcionBienServicio">Descripción bien y/o servicio <span>*</span></label>
                          <textarea class="form-control resize-none" rows="3" v-model="descripcionBienServicio" placeholder="Escribe una descripción aquí..."></textarea>
                        </div>
                      </template>

                  </template>
                </div>
              </div>
              <div class="tab-pane fade" id="series" role="tabpanel" aria-labelledby="profile-tab">
                <div class="row">
                  <div class="col-12 col-sm-12 mb-3">
                    <label class="form-label">Series<span>*</span></label>
                    <v-select
                      :options="listDataSeries"
                      :getOptionLabel="serie => serie.nroSerie"
                      v-model="listSeries"
                      :reduce="serie => serie.id"
                      placeholder="Escribe nombre de vendedor"
                      multiple
                      @option:deselecting="deselectedOption"
                      @option:selecting="serieHandleSelected"
                    >
                      <div slot="no-options">No hay resultados para mostrar.</div>
                    </v-select>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button class="btn btn-danger" @click="hideModal">Cancelar</button>
            <button class="btn btn-primary" @click="createOrUpdate" v-if="!isFormBeingSent">Guardar</button>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="modal-detail" tabindex="-1" aria-labelledby="Modal para mostrar detalles" aria-modal="true" role="dialog">
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title select-none" id="Modal para registrar proveedores">Detalle de compra</h5>
            <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close" @click="hideModalDetail"></button>
          </div>
          <div class="modal-body">
            <div class="table-responsive">
              <table class="table">
                <thead>
                  <tr>
                    <th class="text-center">Cant.</th>
                    <th>Descripción</th>
                    <th>Registradas series</th>
                    <th>P. Unit.</th>
                    <th>Importe</th>
                  </tr>
                </thead>
                <tbody>
                    <tr v-for="(item, index) in detail" :key="index">
                      <td class="xy-center"> {{ item.cantidad }}</td>
                      <td class="y-center"> {{ item.descripcion }} </td>
                      <td class="content-series">
                        <template v-if="item.detail_series.length">
                          <span v-for="(detailSerie, index) in item.detail_series" :key="index" class="span-serie">
                            <div>
                              <b v-if="index != 0" class="separate-serie">/</b> <span class="user-select-all"> {{ detailSerie.serie.nroSerie }} </span>
                            </div>
                          </span>
                        </template>

                        <template v-else>
                          <i class="fas fa-barcode"></i> Sin series
                        </template>
                      </td>
                      <td class="y-center"> {{ shippingInSolesDolares == 'USD' ? '$' : 'S/' }} {{ item.precioUnitario }} </td>
                      <td class="y-center"> {{ shippingInSolesDolares == 'USD' ? '$' : 'S/' }} {{ item.importe }} </td>
                    </tr>
                </tbody>
              </table>
            </div>
          </div>
          <div class="modal-footer">
            <button class="btn btn-danger" @click="hideModalDetail">Regresar <i class="fas fa-undo"></i></button>
          </div>
        </div>
      </div>
    </div>

  </div>
</template>

<style src="./ShoppingComponent.css" scoped></style>
<script src="./ShoppingComponent.js"></script>
