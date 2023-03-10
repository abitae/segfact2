<template>
  <div>
    <LoaderComponent v-if="isLoading"></LoaderComponent>
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <div style="min-height: 300px;">
              <div class="card-title select-none title-with-button align-items-center">
                <div v-if="can(canPrintReport)">
                  <template v-if="listDataShopping.total">
                    <button title="Exportación en Excel" @click="downloadExcel" class="btn btn-excel mb-1">Descargar Excel <i class="far fa-file-excel"></i></button>
                    <button title="Exportación en PDF" @click="downloadPdf" class="btn btn-pdf mb-1">Descargar Pdf <i class="far fa-file-pdf"></i></button>
                  </template>
                </div>
                <!-- <button class="btn btn-primary" @click="openModalCreateShopping">
                  <i class="fas fa-plus"></i> Nuevo
                </button> -->
                <a :href="END_POINT + 'synchronization'" class="btn btn-primary"
                  v-if="can(canCreate)">
                  <i class="fas fa-sync"></i> Sincronización
                </a>
              </div>

              <div class="row">
                <hr v-if="can(canView)">
                <div class="col-12">
                  <h5 class="color-customized mb-3"><i class="fas fa-search"></i> Filtros de búsqueda</h5>
                </div>
              </div>

              <form class="row" action="javascript:;" v-if="can(canView)">
                <div class="mb-3 col-12 col-md-6 col-lg-3 col-xl-2">
                  <label class="form-label" for="idEnterpriseSearch">Empresa <i class="fas fa-question-circle" title="Seleccione una opción para buscar"></i></label>
                  <select
                    id="idEnterpriseSearch"
                    name="idEnterpriseSearch"
                    v-model="idEnterpriseSearch"
                    @change="getListDataShopping()"
                    class="form-select">
                    <option value="" selected>Todos</option>
                    <option v-for="(enterprise, index) in enterprises" :key="index" :value="enterprise.id"> {{ enterprise.fullName }} </option>
                  </select>
                </div>

                <div class="mb-3 col-12 col-md-6 col-lg-3 col-xl-2">
                  <label class="form-label" for="idBranchOfficeSearch">Sucursal <i class="fas fa-question-circle" title="Seleccione una opción para buscar"></i></label>
                  <select
                    id="idBranchOfficeSearch"
                    name="idBranchOfficeSearch"
                    v-model="idBranchOfficeSearch"
                    @change="getListDataShopping()"
                    class="form-select">
                    <option value="" selected>Todos</option>
                    <option v-for="(branchOffice, index) in renderListBranchOffice" :key="index" :value="branchOffice.id"> {{ branchOffice.name }} </option>
                  </select>
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
                  <label class="form-label" for="codigoFacturaSearch">Comprobante <i class="fas fa-question-circle" title="Presione la tecla ENTER para buscar"></i></label>
                  <input class="form-control text-uppercase" type="text"
                    id="codigoFacturaSearch"
                    name="codigoFacturaSearch"
                    v-model="codigoFacturaSearch"
                    @keyup.enter="getListDataShopping()"
                    placeholder="Escríbe aquí..."
                    autocomplete="off">
                </div>
                <div class="mb-3 col-12 col-md-6 col-lg-3 col-xl-2">
                  <label class="form-label" for="nroDocumentSupplierSearch">RUC/DNI Cliente <i class="fas fa-question-circle" title="Presione la tecla ENTER para buscar"></i></label>
                  <input class="form-control" type="text"
                    id="nroDocumentSupplierSearch"
                    name="nroDocumentSupplierSearch"
                    v-model="nroDocumentSupplierSearch"
                    @keyup.enter="getListDataShopping()"
                    placeholder="Escríbe aquí..."
                    autocomplete="off">
                </div>

                <!-- <div class="mb-3 col-12 col-md-6 col-lg-3 col-xl-2">
                  <label class="form-label" for="compraSolesDolaresSearch">C. Soles/Dólares <i class="fas fa-question-circle" title="Seleccione una opción para buscar"></i></label>
                  <select
                    id="compraSolesDolaresSearch"
                    v-model="compraSolesDolaresSearch"
                    @change="getListDataShopping()"
                    class="form-select">
                    <option value="" selected>Todos</option>
                    <option value="soles">Soles</option>
                    <option value="dolares">Dólares</option>
                  </select>
                </div> -->

                <div class="mb-3 col-12 col-md-6 col-lg-3 col-xl-2">
                  <label class="form-label" for="searchByDate">Buscar por fecha <i class="fas fa-question-circle" title="Seleccione una opción para buscar por fecha"></i></label>
                  <select  class="form-select" id="searchByDate" v-model="searchByDate">
                    <option :value="0">Ninguno </option>
                    <option :value="1">Fecha Emisión</option>
                  </select>
                </div>

                <template v-if="searchByDate">
                  <div class="mb-3 col-12 col-md-6 col-lg-3 col-xl-2">
                    <label class="form-label" for="dateEmissionStart">Fecha Emisión <i class="fas fa-question-circle" title="Seleccione una fecha para buscar"></i></label>
                    <input
                      type="date"
                      class="form-control"
                      @change="getListDataShopping()"
                      id="dateEmissionStart"
                      v-model="dateEmissionStart">
                  </div>
                  <div class="mb-3 col-12 col-md-6 col-lg-3 col-xl-2">
                    <label class="form-label" for="dateEmissionEnd">Fecha Emisión <i class="fas fa-question-circle" title="Seleccione una fecha para buscar"></i></label>
                    <input
                      type="date"
                      class="form-control"
                      @change="getListDataShopping()"
                      id="dateEmissionEnd"
                      v-model="dateEmissionEnd">
                  </div>
                </template>
                <input class="d-none" type="submit">
              </form>

              <div v-if="listDataShopping.total && can(canView)" class="col-12">
                <div class="table-responsive">
                  <table class="table table-sm table-hover table-bordered">
                    <thead class="thead-primary">
                      <tr>
                        <th rowspan="2" class="options" v-if="can(canEdit, canUpdateStatus)" >Opciones</th>
                        <!-- <th rowspan="2" class="status">Estado</th> -->
                        <th rowspan="2" class="text-center">Comprobante</th>
                        <th rowspan="2" class="text-center">Empresa</th>
                        <th rowspan="2" class="text-center">Sucursal</th>
                        <th rowspan="2" class="">Cliente</th>
                        <th rowspan="2" class="">Vendedor</th>
                        <th rowspan="2" class="column-type-document">Tipo Comprobante</th>
                        <th rowspan="2" class="text-center">Compra</th>
                        <!-- <th rowspan="2" class="text-center">M. Dólares</th> -->
                        <!-- <th rowspan="2" class="text-center">Tipo cambio</th> -->
                        <th rowspan="2" class="text-center">Sub total</th>
                        <th rowspan="2" class="text-center">IGV</th>
                        <th rowspan="2" class="text-center">Monto total</th>
                        <th colspan="2" class="text-center">Comisión</th>
                        <th rowspan="2" class="text-center">Archivos</th>
                        <!-- <th rowspan="2" class="text-center">Guía R.</th> -->
                        <th rowspan="2" class="date-issue text-center">Fecha emisión</th>
                      </tr>
                      <tr>
                        <th class="text-center">Venta</th>
                        <th class="text-center">Contacto</th>
                      </tr>
                    </thead>
                    <tbody class="vertical-align-middle">
                      <tr v-for="(item,index) in listDataShopping.data" :key="index">
                        <td class="options" v-if="can(canEdit, canUpdateStatus)">
                          <button class="btn btn-sm btn-primary"
                            v-if="can(canEdit)"
                            @click="openModalEditShopping(item)"
                          >
                            <i class="far fa-eye"></i>
                          </button>
                          <button :class="`btn btn-sm btn-${ item.is_active ? 'success' : 'danger' }`"
                            v-if="can(canUpdateStatus)"
                            @click="updataStatus(item)"
                          >
                            <i v-if="item.is_active" class="far fa-check-circle"></i>
                            <i v-else class="far fa-times-circle"></i>
                          </button>
                        </td>
                        <td class="text-center cursor-default user-select-all">
                          <span class="cursor-pointer" @click="copiedAtClipboard(item.codigoFactura)">{{ item.codigoFactura }}</span>
                        </td>
                        <td>
                          {{ item.enterprise.fullName }}
                        </td>
                        <td>{{ item.branch_office.name }}</td>
                        <td class="column-supplier">
                          <span class="select-all cursor-pointer" @click="copiedAtClipboard(item.cliente.nroDocument)">
                            {{ item.cliente.nroDocument }}
                          </span> - {{ item.cliente.fullName }}
                        </td>
                        <td> {{ item.vendedor.name }} </td>
                        <td> {{ item.tipo_comprobante.nombre }} </td>
                        <td class="text-capitalize text-center"> {{ renderTextSale(item.compraSolesDolares) }} </td>
                        <td class="text-center"> S/ {{ item.monto }} </td>
                        <td class="text-center"> S/ {{ item.igv }} </td>
                        <td class="text-center"> S/ {{ item.montoTotal }} </td>
                        <td class="text-center"> S/ {{ item.saleCommission }} </td>
                        <td class="text-center"> S/ {{ item.contactCommission }} </td>
                        <td class="text-center">
                          <div class="btn-group mt-2 me-1 dropstart">
                            <button type="button" style="inline-size: 97px;" :class="`btn bgn-block btn-sm waves-effect waves-light dropdown-toggle ${ !item.warrantyStartDate ? 'btn-info' : 'btn-primary'}`" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                              <i class="mdi mdi-chevron-left"></i>
                              {{ !item.warrantyStartDate ? 'Generar' : 'Documentos' }}
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="javascript:;"
                                  @click="downloadAttachDocument(item.attachDocument, 'purchase_order_document')"> <i class="fas fa-download"></i> Orden Compra</a>
                                <a class="dropdown-item" href="javascript:;"
                                  @click="downloadAttachDocument(item.attachRemissionGuideDocument, 'remission_guide_document')"> <i class="fas fa-download"></i> Guía Remisión</a>
                                <template v-if="item.warrantyStartDate">
                                  <a class="dropdown-item" :href="`${END_POINT}generate-document-cci?idSale=${item.id}`" target="_blank"> <i class="fas fa-eye"></i> Carta de CCI</a>
                                  <a class="dropdown-item" :href="`${END_POINT}generate-document-letter-warranty?idSale=${item.id}`" target="_blank"> <i class="fas fa-eye"></i> Carta de Garantía</a>
                                </template>
                                <template v-else>
                                  <a class="dropdown-item" href="javascript:;" @click="openModalGenerateDocuments(item.id)"> <i class="fas fa-file-alt"></i> Generar carta CCI, Garantía</a>
                                </template>
                            </div>
                          </div>
                        </td>
                        <td class="text-center"> {{ item.fechaEmision | formatDate }}</td>
                      </tr>
                    </tbody>
                  </table>
                  <pagination class="select-none" :limit="5" size="small" :data="listDataShopping" @pagination-change-page="getListDataShopping">
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

    <div class="modal fade" id="modal-shopping" tabindex="-1" aria-labelledby="Modal para registrar ventas" aria-modal="true" role="dialog">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title select-none" id="Modal para registrar ventas"> {{ modalTitle }} venta </h5>
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
                    aria-selected="false">
                    Series
                  </a>
                </li>
            </ul>
            <div class="tab-content p-3" id="myTabContent">
              <div class="tab-pane fade active show" id="general" role="tabpanel" aria-labelledby="home-tab">
                <div class="row">
                  <div class="col-12 col-sm-12 col-md-5 col-lg-4 mb-3">
                    <label class="form-label">N° Doc. cliente<span>*</span> </label>
                    <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected">
                      <input type="number" class="form-control" placeholder="00000000000" v-model="nroDocumentSupplier" @keyup.enter="searchCustomer(nroDocumentSupplier)">
                      <span class="input-group-btn input-group-append">
                        <button class="btn btn-primary bootstrap-touchspin-up" type="button" @click="searchCustomer(nroDocumentSupplier)">
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
                    <div class="col-12 col-sm-12 col-md-4 mb-3">
                      <label class="form-label">Adjuntar orden de compra</label>
                      <label for="attachDocument" class="input-file">
                        <span v-if="nameFile"> {{ nameFile }} </span>
                        <span v-else><i class="fas fa-file-upload"></i> Seleccione PDF</span>
                      </label>
                      <input type="file" class="form-control d-none" id="attachDocument" accept="application/pdf" @change="changeFile($event)">
                    </div>
                    <div class="col-12 col-sm-12 col-md-8 mb-3">
                      <label class="form-label" for="idUsuario">Vendedor<span>*</span> </label>
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
                      <label class="form-label" for="fechaEmision">Fecha de Emisión<span>*</span></label>
                      <input type="date" class="form-control" id="fechaEmision" :min="dateInitWorking" v-model="fechaEmision">
                    </div>
                    <div class="mb-3 col-12 col-sm-12 col-md-4 col-lg-4">
                      <label class="form-label" for="codigoFactura">N° de Factura<span>*</span></label>
                      <input type="text" class="form-control" id="codigoFactura" v-model="codigoFactura" placeholder="Ejem: F0000" autocomplete="off">
                    </div>
                    <div class="mb-3 col-12 col-sm-12 col-md-4 col-lg-4">
                      <label class="form-label" for="idTipoComprobante">Tipo comprobante<span>*</span></label>
                      <select class="form-select" id="idTipoComprobante" v-model="idTipoComprobante">
                        <option value=""> __Seleccione__ </option>
                        <option v-for="(typeDocument, index) in listTypeDocuments" :key="index" :value="typeDocument.id"> {{ typeDocument.nombre }} </option>
                      </select>
                    </div>
                    <div class="mb-3 col-12 col-sm-12 col-md-4 col-lg-4">
                      <label class="form-label" for="compraSolesDolares">Venta en Soles/Dólares<span>*</span></label>
                      <select class="form-select" id="compraSolesDolares" v-model="compraSolesDolares" @change="compraSolesDolaresHandleChange">
                        <option value=""> __Seleccione__ </option>
                        <option value="soles"> Soles </option>
                        <option value="dolares"> Dólares </option>
                      </select>
                    </div>
                    <template v-if="compraSolesDolares === 'dolares'">
                      <div class="mb-3 col-12 col-sm-12 col-md-4 col-lg-4">
                        <label class="form-label" for="monto">Monto<span>*</span></label>
                        <input type="number" min="0" step="0.01" class="form-control" id="monto" v-model="monto" placeholder="100.00">
                      </div>

                      <div class="mb-3 col-12 col-sm-12 col-md-4 col-lg-4">
                        <label class="form-label" for="tipoDeCambio">Tipo de cambio<span>*</span></label>
                        <input type="number" min="0" step="0.01" class="form-control" id="tipoDeCambio" v-model="tipoDeCambio" @blur="calculateOperationInDolares" placeholder="0.000">
                      </div>
                    </template>
                    <template v-if="Boolean(compraSolesDolares)">
                      <div class="mb-3 col-12 col-sm-12 col-md-4 col-lg-4">
                        <label class="form-label" for="montoVentaSoles">Sub total<span>*</span></label>
                        <input type="number" min="0" step="0.01" class="form-control" id="montoVentaSoles" v-model="montoVentaSoles" @blur="calculateOperationInSoles"
                        :readonly="compraSolesDolares == 'dolares'"
                        placeholder="0.000">
                      </div>
                      <!-- <div class="mb-3 col-12 col-sm-12 col-md-4 col-lg-4">
                        <label class="form-label" for="montoVentaDolares">Monto venta dólares<span>*</span></label>
                        <input type="number" min="0" step="0.01" class="form-control" id="montoVentaDolares" v-model="montoVentaDolares" placeholder="100.00">
                      </div> -->
                      <div class="mb-3 col-12 col-sm-12 col-md-4 col-lg-4">
                        <label class="form-label" for="igv">IGV</label>
                        <input type="number" min="0" step="0.01" class="form-control" readonly id="igv" v-model="igv" placeholder="0.00">
                      </div>

                      <div class="mb-3 col-12 col-sm-12 col-md-4 col-lg-4">
                        <label class="form-label" for="montoTotal">Monto total</label>
                        <input type="number" min="0" step="0.01" class="form-control" readonly id="montoTotal" v-model="montoTotal" placeholder="0.00">
                      </div>

                      <div class="mb-3 col-12 col-sm-12 col-md-4 col-lg-4">
                        <label class="form-label" for="saleCommission">Comisión Venta <b>1%</b> </label>
                        <input type="number" min="0" step="0.01" class="form-control" id="saleCommission" v-model="saleCommission" placeholder="0.00">
                      </div>

                      <div class="mb-3 col-12 col-sm-12 col-md-4 col-lg-4">
                        <label class="form-label" for="contactCommission">Comisión contacto <b>1%</b> </label>
                        <input type="number" min="0" step="0.01" class="form-control" id="contactCommission" v-model="contactCommission" placeholder="0.00">
                      </div>

                      <div class="mb-3 col-12">
                        <label class="form-label" for="descripcionBienServicio">Descripción bien y/o servicio<span>*</span></label>
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
                      placeholder="Seleccione las series"
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

    <div class="modal fade" id="modal-sale-list" tabindex="-1" aria-labelledby="modalSaleList" aria-hidden="true">
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="listPucharse">Comprobante {{sale.codigoFactura}}</h5>
            <button type="button" class="btn-close" @click="closeModal('modal-sale-list')" aria-label="Cerrar"></button>
          </div>
          <form id="form-sale" class="form" action="javascript:;">
            <div class="modal-body">

              <div class="row">
                <!-- <div class="col-sm-12 col-md-4 mb-4">
                  <label for="idSeller">Vendedor</label>
                  <input type="text" class="form-control" :value="sale.vendedor && sale.vendedor.name" readonly>
                </div>

                <div class="col-sm-12 col-md-4 md-4">
                  <label class="form-label" style="margin-bottom: 0.3em;">Adjuntar orden de compra</label>
                  <label for="attachDocument" class="input-file">
                    <span v-if="nameFile"> {{ nameFile }} </span>
                    <span v-else><i class="fas fa-file-upload"></i> Seleccione PDF</span>
                  </label>
                  <input type="file" class="form-control d-none" id="attachDocument" accept="application/pdf" @change="changeFile($event)">
                </div> -->

                <div class="col-sm-12 col-md-12 mb-3">
                  <div class="content-state-sunat">
                    <img :src="renderIconState(sale.estadoEnvioSunat)" height="30" :alt="`Imagen de ${sale.estadoEnvioSunat}`">
                    <p> Estado de documento en sunat <br> <b class="text-uppercase"> {{ sale.estadoEnvioSunat }}</b> </p>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-sm-12 col-md-6 mb-3">
                  <label class="mb-0" for="descripcionBienServicio">Descripción de Bien/Servicio</label>
                  <textarea
                    rows="2"
                    class="form-control"
                    id="descripcionBienServicio"
                    placeholder="Escríbe una descripción aquí..."
                    v-model="sale.descripcionBienServicio"
                    readonly></textarea>
                </div>
                <div class="col-sm-12 col-md-6 mb-3">
                  <div class="row" v-if="sale.cliente">
                    <div class="col-sm-12">
                      <h5 class="m-0">Cliente</h5>
                    </div>
                    <div class="col-sm-12">
                      <div class="client-info-content">
                        <div>Nombre:</div>
                        <div>{{ sale.cliente.fullName || '-' }}</div>
                      </div>
                    </div>
                    <div class="col-sm-12">
                      <div class="client-info-content">
                        <div>RUC:</div>
                        <div>{{ sale.cliente.nroDocument || '-' }}</div>
                      </div>
                    </div>
                    <div class="col-sm-12">
                      <div class="client-info-content">
                        <div>Dirección:</div>
                        <div>{{ sale.cliente.address || '-' }}</div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-sm-12 mb-3">
                  <div class="table-responsive">
                    <table class="table table-borderd table-sm table-primary text-center">
                      <thead>
                        <tr>
                          <th>Fecha de emisión</th>
                          <th>Condición de pago</th>
                          <th>Tipo de pago</th>
                          <th>Número de guía</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td class="py-4" style="inline-size: 25%"> {{ sale.created_at | parseDateHour }} </td>
                          <td class="py-4" style="inline-size: 25%"> {{ sale.condicionPago }} </td>
                          <td class="py-4" style="inline-size: 25%"> {{ sale.compraSolesDolares == 'PEN' ? 'Soles' : 'Dólares' }} </td>
                          <td class="py-4" style="inline-size: 25%"> {{ sale.nroGuiaRemision }} </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-sm-12">
                  <div class="table-responsive">
                    <table class="table table-sm table-hover">
                      <thead>
                        <tr>
                          <th>Cant.</th>
                          <th>Descripción</th>
                          <th>Series</th>
                          <th>Precio</th>
                          <th class="text-right">Importe</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="(detail, index) in sale.venta_detalle" :key="index">
                          <td class="xy-center">{{ Number(detail.cantidad) }}</td>
                          <td class="y-center">{{ detail.descripcion }} </td>
                          <td class="y-center">
                            <div class="content-series" >
                              <template v-if="detail.list_series.length">
                                <small class="" v-for="(item,indexSerie) in detail.list_series" :key="indexSerie">
                                  {{ item.serie && item.serie.nroSerie }}
                                </small>
                              </template>
                              <template  v-else>
                                <span><i class="fas fa-barcode"></i> Sin serie(s)</span>
                              </template>
                            </div>
                          </td>
                          <td class="y-center price-import">S/ {{ detail.precioUnitario | parseCentimos }}</td>
                          <td class="y-center text-right price-import">S/ {{ detail.importe }}</td>
                        </tr>
                        <tr>
                          <th colspan="4" class="text-right">Importe total</th>
                          <td class="text-right">S/ {{sale.montoTotal}}</td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-danger" @click="closeModal('modal-sale-list')">Cerrar</button>
              <!-- <button type="button" class="btn btn-primary" @click="sendFormListSale()">Actualizar</button> -->
            </div>
          </form>
        </div>
      </div>
    </div>

    <div class="modal fade" id="modal-generate-documents" tabindex="-1" aria-labelledby="modalSaleList" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="listPucharse">Generar documentos</h5>
            <button type="button" class="btn-close" @click="closeModal('modal-generate-documents')" aria-label="Cerrar"></button>
          </div>
          <form id="form-sale" class="form" action="javascript:;">
            <div class="modal-body">
              <div class="row">
                <div class="col-sm-12 col-md-6 mb-3">
                  <label class="form-label" for="warrantyPeriod">Tiempo de garantía</label>
                  <select class="form-select" id="warrantyPeriod" v-model="warrantyPeriod">
                    <option value="years">Años</option>
                    <option value="months">Meses</option>
                    <option value="weeks">Semanas</option>
                    <option value="days">Días</option>
                  </select>
                </div>
                <div class="col-sm-12 col-md-6 mb-3 user-select-none">
                  <label class="form-label" for="warrantyPeriodQuantity">Cantidad en garantía</label>
                  <input type="number" id="warrantyPeriodQuantity" v-model.number="warrantyPeriodQuantity" class="form-control" placeholder="Ejem: 3">
                </div>
                <div class="col-12 mb-3">
                  <label class="form-label" for="start_date_warranty">Fecha inicio de documentos</label>
                  <input type="date" id="start_date_warranty" v-model="startDateWarranty" class="form-control" placeholder="Ejem: 3">
                </div>
              </div>
              <div class="col-sm-12 user-select-none">
                <p>Los siguientes documentos se van a generar después de hacer click en el botón <span class="badge bg-primary px-3 py-1">GENERAR</span> </p>
                <ul>
                  <li>Carta de autorización en CCI</li>
                  <li>Carta de garantía comercial</li>
                </ul>
                <small class="fw-bold text-muted">Estos documentos unicamente se van a generar una sola vez</small>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-danger" @click="closeModal('modal-generate-documents')">Cancelar</button>
              <button type="button" class="btn btn-primary" @click="generateDocuments">Generar <i class="far fa-paper-plane"></i></button>
            </div>
          </form>
        </div>
      </div>
    </div>

  </div>
</template>

<style src="./SaleComponent.css" scoped></style>
<script src="./SaleComponent.js"></script>
