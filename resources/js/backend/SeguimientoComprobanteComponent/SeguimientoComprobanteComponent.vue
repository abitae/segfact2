<script src="./SeguimientoComprobanteComponent.js"></script>

<template>
  <div>
    <LoaderComponent v-if="isLoading"></LoaderComponent>
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <div style="min-block-size: 300px;">
              <div class="col-12 text-center">
                <h5 class="fw-bold">ESTADOS DE COMPROBANTE</h5>
              </div>
              <div class="col-sm-12 text-center">
                <div class="color-description dateBg-issue">
                  <p> <i class="far fa-square icon-color-issue"></i> Emitido</p>
                </div>
                <div class="color-description dateBg-success">
                  <p> <i class="fas fa-square icon-color-success"></i> Pagados</p>
                </div>
                <div class="color-description dateBg-warning">
                  <p> <i class="fas fa-square icon-color-warning"></i> Por cobrar</p>
                </div>
                <div class="color-description dateBg-danger">
                  <p> <i class="fas fa-square icon-color-danger"></i> Vencidos</p>
                </div>
                <div class="color-description dateBg-nullable">
                  <p> <i class="fas fa-square icon-color-nullable"></i> Anulados</p>
                </div>
              </div>
              <hr>
              <div class="select-none">
                <h5 class="mb-0 text-center text-md-start mb-3" v-if="can(canPrintReport)">
                  <button title="Exportación en Excel" class="btn btn-excel mb-1" @click="downloadExcel">
                    Descargar Excel <i class="far fa-file-excel"></i>
                  </button>
                  <button title="Exportación en PDF" class="btn btn-pdf mb-1" @click="downloadPdf">
                    Descargar PDF <i class="far fa-file-pdf"></i>
                  </button>
                </h5>
              </div>
              <div class="row justify-content-between">
                <div class="col-12 col-sm-12 mb-3">
                  <h5 class="color-customized">
                    <i class="fas fa-search"></i> Filtros de búsqueda
                  </h5>
                </div>
              </div>
              <!-- START FILTERS -->
              <form class="row" action="javascript:;">
                <div class="mb-3 col-12 col-md-6 col-lg-3 col-xl-2">
                  <label class="form-label" for="enterpriseSearch">Empresa <i class="fas fa-question-circle" title="Seleccione una opción para buscar"></i></label>
                  <select
                    id="enterpriseSearch"
                    name="enterpriseSearch"
                    @change="getListSale()"
                    v-model="enterpriseSearch"
                    class="form-select">
                    <option value="" selected>Todos</option>
                    <option v-for="(enterprise, index) in listEnterprise" :key="index" :value="enterprise.id">
                      {{ enterprise.fullName }}
                    </option>
                  </select>
                </div>
                <div class="mb-3 col-12 col-md-6 col-lg-3 col-xl-2">
                  <label class="form-label" for="branchOfficeSearch">Sucursales <i class="fas fa-question-circle" title="Seleccione una opción para buscar"></i></label>
                  <select
                    id="branchOfficeSearch"
                    name="enterpriseSearch"
                    @change="getListSale()"
                    v-model="idBranchOfficeSearch"
                    class="form-select">
                    <option value="" selected>Todos</option>
                    <option v-for="(branch, index) in renderListBranchOffice" :key="index" :value="branch.id">
                      {{ branch.name }}
                    </option>
                  </select>
                </div>

                <div class="mb-3 col-12 col-md-6 col-lg-3 col-xl-2">
                  <label class="form-label" for="estadoDocumentoSearch">Estado de doc. <i class="fas fa-question-circle" title="Seleccione una opción para buscar"></i></label>
                  <select
                    id="estadoDocumentoSearch"
                    name="estadoDocumentoSearch"
                    v-model="estadoDocumentoSearch"
                    @change="getListSale()"
                    class="form-select">
                    <option value="" selected>Todos</option>
                    <option v-for="(documentState, index) in listDocumentStates" :key="index" :value="documentState.id"> {{ documentState.nombre }} </option>
                  </select>
                </div>

                <div class="mb-3 col-12 col-md-6 col-lg-3 col-xl-2">
                  <label class="form-label" for="codigoFacturaSearch">Comprobante <i class="fas fa-question-circle" title="Presione la tecla ENTER para buscar"></i></label>
                  <input class="form-control" type="text"
                    id="codigoFacturaSearch"
                    name="codigoFacturaSearch"
                    v-model="codigoFacturaSearch"
                    @keyup.enter="getListSale()"
                    placeholder="Buscar..."
                    autocomplete="off">
                </div>

                <div class="mb-3 col-12 col-md-6 col-lg-3 col-xl-2">
                  <label class="form-label" for="nroDocumentCustomerSearch">RUC/DNI cliente <i class="fas fa-question-circle" title="Presione la tecla ENTER para buscar"></i></label>
                  <input class="form-control" type="text"
                    id="nroDocumentCustomerSearch"
                    name="nroDocumentCustomerSearch"
                    v-model="nroDocumentCustomerSearch"
                    @keyup.enter="getListSale()"
                    placeholder="Buscar..."
                    autocomplete="off">
                </div>

                <div class="mb-3 col-12 col-md-6 col-lg-3 col-xl-2">
                  <label class="form-label" for="nameUserSearch">
                    Vendedor <i class="fas fa-question-circle" title="Presione la tecla ENTER para buscar"></i>
                  </label>
                  <select
                    id="nameUserSearch"
                    name="nameUserSearch"
                    v-model="nameUserSearch"
                    @change="getListSale()"
                    class="form-select">
                    <option value="" selected>Todos</option>
                    <option v-for="(user, index) in users" :key="index" :value="user.id"> {{ user.name }} </option>
                  </select>
                </div>

                <div class="mb-3 col-12 col-md-6 col-lg-3 col-xl-2">
                  <label class="form-label" for="bankIdSearch">Banco <i class="fas fa-question-circle" title="Seleccione una opción para buscar"></i></label>
                  <select
                    id="bankIdSearch"
                    name="bankIdSearch"
                    @change="getListSale()"
                    v-model="bankIdSearch"
                    class="form-select">
                    <option value="" selected>Todos</option>
                    <option v-for="(bank, index) in banks" :key="index" :value="bank.id">
                      {{ bank.name }}
                    </option>
                  </select>
                </div>

                <div class="mb-3 col-12 col-md-6 col-lg-3 col-xl-2">
                  <label class="form-label" for="searchByDate">Buscar por fecha <i class="fas fa-question-circle" title="Seleccione una opción para buscar"></i></label>
                  <select
                    id="searchByDate"
                    name="searchByDate"
                    @change="getListSale()"
                    v-model="searchByDate"
                    class="form-select">
                    <option value="" selected>Seleccione</option>
                    <option value="fechaEmision">Fecha emisión</option>
                    <option value="fechaVencimiento">Fecha vencimiento</option>
                  </select>
                </div>

                <template v-if="searchByDate">
                  <div class="mb-3 col-12 col-md-6 col-lg-3 col-xl-2">
                    <label class="form-label" for="dateStartSearch">Fecha inicio <i class="fas fa-question-circle" title="Seleccione una fecha para buscar"></i></label>
                    <input
                      type="date"
                      class="form-control"
                      @change="getListSale()"
                      id="dateStartSearch"
                      v-model="dateStartSearch">
                  </div>
                  <div class="mb-3 col-12 col-md-6 col-lg-3 col-xl-2">
                    <label class="form-label" for="dateEndSearch">Fecha fin <i class="fas fa-question-circle" title="Seleccione una fecha para buscar"></i></label>
                    <input
                      type="date"
                      class="form-control"
                      @change="getListSale()"
                      id="dateEndSearch"
                      v-model="dateEndSearch">
                  </div>
                </template>
                <input class="d-none" type="submit">
              </form>
              <!-- END FILTERS -->
              <hr class="mt-0">
              <!-- START SHOW STATISTICS -->
              <div class="row justify-content-between align-items-center">
                <div class="col-12 col-md-3" v-if="can(isAdmin)">
                  <div class="form-check form-switch">
                    <input class="form-check-input d-inline-block"
                      :class="{'cursor-pointer': nameUserSearch}"
                      type="checkbox" role="switch" id="flexSwitchCheckDefault" v-model="showStatistics" :disabled="!nameUserSearch">
                    <label class="form-check-label user-select-none"
                      :class="{'cursor-pointer': nameUserSearch}"
                      for="flexSwitchCheckDefault"> Ver estadisticas</label>
                  </div>
                </div>
                <div class="col-12 col-md-5 col-lg-3 col-xl-2 mb-3">
                  <select v-model="paginate" class="form-select">
                    <option :value="10">Mostrar de 10</option>
                    <option :value="20">Mostrar de 20</option>
                    <option :value="30">Mostrar de 30</option>
                    <option :value="40">Mostrar de 40</option>
                  </select>
                </div>
              </div>
              <div v-if="showStatistics" class="row justify-content-center mb-3">
                <div class="col-12 col-md-2 text-center">
                  <img :src="END_POINT + 'images/view_quotation_tracking/money.png'" class="image-thumbnail" alt="Image money">
                  <p class="d-block mb-0 mt-1">Cuota asignada</p>
                  <h5>S/ {{ statistics.cuota }} </h5>
                </div>
                <div class="col-12 col-md-2 text-center">
                  <img :src="END_POINT + 'images/view_quotation_tracking/money.png'" class="image-thumbnail" alt="Image money">
                  <p class="d-block mb-0 mt-1">Monto total</p>
                  <h5>S/ {{ statistics.totalAmount }}</h5>
                </div>
                <div class="col-12 col-md-2 text-center" v-if="statistics.figure != 'completed'">
                  <img :src="END_POINT + 'images/view_quotation_tracking/decrease.png'" class="image-thumbnail" alt="Image decrease">
                  <p class="d-block mb-0 mt-1">Estado de cuota</p>
                  <h5>Faltante</h5>
                </div>
                <div v-else class="col-12 col-md-2 text-center">
                  <img :src="END_POINT + 'images/view_quotation_tracking/increase.png'" class="image-thumbnail" alt="Image decrease">
                  <p class="d-block mb-0 mt-1">Estado de cuota</p>
                  <h5>Completado</h5>
                </div>

                <div class="col-12 col-md-2 text-center">
                  <img :src="END_POINT + 'images/view_quotation_tracking/percentage.png'" class="image-thumbnail" alt="Image precentage">
                  <p class="d-block mb-0 mt-1">Estado de cuota</p>
                  <!-- <h5 class="text-danger"> - 1.7% </h5> -->
                  <h5>
                    <span :class="statistics.percentageShare < 0 ? 'text-danger' : 'text-success' ">{{ statistics.percentageShare }}%</span>
                  </h5>
                </div>

                <div class="col-12 col-md-2 text-center">
                  <img :src="END_POINT + 'images/view_quotation_tracking/money.png'" class="image-thumbnail" alt="Image money">
                  <p class="d-block mb-0 mt-1">Excedente</p>
                  <h5>S/ {{ statistics.surplus }}</h5>
                </div>

                <div class="col-12 col-md-2 text-center">
                  <img :src="END_POINT + 'images/view_quotation_tracking/money.png'" class="image-thumbnail" alt="Image money">
                  <p class="d-block mb-0 mt-1">Comisión</p>
                  <h5>S/ {{ statistics.commission }}</h5>
                </div>

              </div>
              <!-- END SHOW STATISTICS -->
              <div v-if="listSale.total && can(canView)" class="col-12">
                <div class="table-responsive">
                  <table class="table table-sm table-bordered">
                    <thead class="thead-primary">
                      <tr>
                        <th class="options" v-if="can(canEdit, canUpdateStatus)">Opciones</th>
                        <!-- <th class="status">Estado</th> -->
                        <!-- <th class="text-center">N° F. Compra</th> -->
                        <th class="column-document">Documentos</th>
                        <th class="text-center">Comprobante</th>
                        <th class="text-center">C.U. Ejec.</th>
                        <th class="text-center">N° Siaf</th>
                        <th>Cliente</th>
                        <th class="text-center">Monto</th>
                        <th class="text-center">Detracción</th>
                        <th class="text-center">Retención</th>
                        <th class="text-center">Importe</th>
                        <th class="text-center">Responsable</th>
                        <th class="text-center">Estado</th>
                        <th class="text-center">Dif. en días</th>
                        <th class="date-issue text-center">F. Emisión</th>
                        <th class="date-issue text-center">F. Expiración</th>
                        <th class="date-issue text-center">Banco</th>
                        <th class="date-issue text-center">F. Pago</th>
                        <th v-if="[1,2].includes(authenticated.id)">Arreglado</th>
                        <th class="date-issue" v-if="[1,2].includes(authenticated.id)">Observación</th>
                        <!-- <th class="text-center">Observación</th> -->
                      </tr>
                    </thead>
                    <tbody class="vertical-align-middle">
                      <tr v-for="(sale,index) in listSale.data" :key="index"
                        :class="setBackgroundColor(sale.estadoDocumento, sale.fechaVencimiento)">
                        <td class="option-buttons" v-if="can(canEdit, canUpdateStatus)">
                          <template v-if="sale.estadoDocumento != 5">
                            <button
                              v-if="can(canEdit)"
                              @click="openModalEditSale(sale)"
                              class="btn btn-sm btn-primary"
                            > <i class="far fa-edit"></i>
                            </button>
                            <button
                              v-if="can(canUpdateStatus)"
                              @click="updataStatus(sale)"
                              :class="`btn btn-sm btn-${ sale.is_active ? 'success' : 'danger' }`">
                              <i v-if="sale.is_active" class="far fa-check-circle"></i>
                              <i v-else class="far fa-times-circle"></i>
                            </button>
                            <button
                              @click="cancelVoucher(sale)"
                              class="btn btn-sm btn-secondary">
                              <i class="fas fa-ban"></i>
                            </button>
                          </template>
                          <template v-else>
                            <i class="fas fa-ban fw-bold"></i> Anulado
                          </template>
                        </td>

                        <!-- <td class="status">
                          <span v-if="sale.is_active" class="badge rounded-pill bg-success registration-status">Activo</span>
                          <span v-else class="badge rounded-pill bg-danger registration-status">Desactivado</span>
                        </td>
                        <td class="text-center cursor-default user-select-all">
                          <span class="cursor-pointer" @click="copiedAtClipboard(sale.factura_compra.codigoFactura)">{{ sale.factura_compra.codigoFactura }}</span>
                        </td> -->

                        <td class="column-document">
                          <div class="btn-group mt-2 me-1 dropstart">
                            <button type="button" style="inline-size: 97px;" :class="`btn bgn-block btn-sm waves-effect waves-light dropdown-toggle btn-primary`" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                              Documentos
                              <i class="mdi mdi-chevron-right"></i>
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="javascript:;" @click="downloadAttachDocument(sale.idSale, 'purchase_order_document')"> <i class="fas fa-download"></i> Orden Compra</a>
                                <a class="dropdown-item" href="javascript:;" @click="downloadAttachDocument(sale.idSale, 'remission_guide_document')"> <i class="fas fa-download"></i> Guía Remisión</a>
                                <a class="dropdown-item" :href="`${END_POINT}generate-document-cci?idSale=${sale.idSale}`" target="_blank"> <i class="fas fa-eye"></i> Carta de CCI</a>
                                <a class="dropdown-item" :href="`${END_POINT}generate-document-letter-warranty?idSale=${sale.idSale}`" target="_blank"> <i class="fas fa-eye"></i> Carta de Garantía</a>
                            </div>
                          </div>
                        </td>
                        <td class="text-center">
                          <a :href="END_POINT+'history/'+sale.nroFacturaVenta">{{ sale.nroFacturaVenta }}
                           <span v-if="sale.refactoringCode">/ {{sale.refactoringCode}}</span>
                          </a>
                          <!-- <span class="cursor-pointer"></span> -->
                        </td>
                        <td class="text-center cursor-default user-select-all"> {{ sale.codigoUnidadEjecutora }} </td>
                        <td class="text-center cursor-default user-select-all"> {{ sale.nroSiaf }} </td>
                        <td class="column-supplier">
                          <span class="select-all cursor-pointer" @click="copiedAtClipboard(sale.cliente.nroDocument)">
                            {{ sale.cliente.nroDocument }}
                          </span> <br>
                          {{ sale.cliente.fullName }}
                        </td>
                        <td class="text-center"> S/ {{ sale.monto }} </td>
                        <td class="text-center"> S/ {{ sale.detraccion }} </td>
                        <td class="text-center"> S/ {{ sale.retencion }} </td>
                        <td class="text-center"> S/ {{ sale.montoTotal }} </td>
                        <td class="text-left"> {{ sale.usuario.name }} </td>
                        <td class="text-center"> {{ sale.estado_de_documento.nombre }} </td>
                        <td class="text-left" style="min-inline-size: 100px;"> {{ setTextDescription(sale.fechaVencimiento, sale.estadoDocumento) }} </td>
                        <td class="text-center"> {{ sale.fechaEmision | formatDate }}</td>
                        <td class="text-center"> {{ sale.fechaVencimiento | formatDate }}</td>
                        <td class="text-left"> {{ sale.bank && sale.bank.name || '-' }}</td>
                        <td class="text-center" v-if="[1,2].includes(authenticated.id)"> {{ sale.fechaPago | formatDate }}</td>
                        <td class="text-center" v-if="[1,2].includes(authenticated.id)">
                          <input type="checkbox" class="cursor-pointer" :checked="sale.arranged" @change="markAsArranged(sale.id, sale.arranged)">
                        </td>
                        <td> {{ sale.actionesObservaciones }}</td>
                      </tr>
                    </tbody>
                  </table>
                  <pagination class="select-none"
                    :limit="5"
                    size="small"
                    :data="listSale"
                    @pagination-change-page="getListSale"
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

    <div class="modal fade" id="modal-sale" tabindex="-1" aria-labelledby="modal-para-registrar-ventas" aria-modal="true" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title select-none" id="modal-para-registrar-ventas"> Opción de seguimiento</h5>
            <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close" @click="hideModal"></button>
          </div>
          <div class="modal-body">
            <template v-if="currentSale">
              <ul class="nav nav-tabs" id="myTab" role="tablist">
                <template v-if="currentSale.estadoDocumento != 4">
                  <li class="nav-item col-sm-6" role="presentation">
                    <button class="nav-link fw-bold w-100" id="home-tab" type="button"
                      data-bs-toggle="tab" data-bs-target="#home" role="tab" aria-controls="home" aria-selected="true">
                      Documento
                    </button>
                  </li>
                </template>
                <li class="nav-item col-sm-6" role="presentation">
                  <button class="nav-link fw-bold w-100" id="profile-tab" type="button"
                    data-bs-toggle="tab" data-bs-target="#profile" role="tab" aria-controls="profile" aria-selected="false">
                    Edición datos
                  </button>
                </li>
              </ul>
              <div class="tab-content pt-3" id="myTabContent">
                <div class="tab-pane fade show" v-if="currentSale && currentSale.estadoDocumento != 4" id="home" role="tabpanel" aria-labelledby="home-tab">
                  <div class="row" v-if="currentSale">
                    <div class="col-sm-12 col-md-6 mb-3">
                      <label for="" class="form-label">ESTADO DE DOCUMENTO </label>
                      <input type="text" class="form-control text-uppercase" readonly :value="currentSale.estado_de_documento.nombre">
                    </div>
                    <div class="col-sm-12 col-md-6 mb-3">
                      <label for="" class="form-label">ACTUALIZAR A </label>
                      <input type="text" class="form-control text-uppercase" readonly :value="nextState.nombre">
                    </div>
                    <div class="col-sm-12 mb-3">
                      <label for="current-sale-description" class="form-label">Observación </label>
                      <textarea type="text" id="current-sale-description" class="form-control" style="max-block-size: 100px" v-model="description"></textarea>
                    </div>
                    <div class="col-12 col-sm-12 text-end">
                      <button class="btn btn-danger" @click="hideModal">
                        Cancelar
                      </button>
                      <button class="btn btn-primary" @click="updateState" v-if="!isFormBeingSent">
                        Guardar <i class="far fa-paper-plane"></i>
                      </button>
                    </div>
                  </div>
                </div>
                <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                  <div class="row">
                    <div class="mb-3 col-12 col-sm-12 col-md-6">
                      <label class="form-label" for="codigoUnidadEjecutora">Código Unidad Ejecutora</label>
                      <input type="text" class="form-control" id="codigoUnidadEjecutora" v-model="codigoUnidadEjecutora" autocomplete="off" placeholder="Ejem: 00000">
                    </div>
                    <div class="mb-3 col-12 col-sm-12 col-md-6">
                      <label class="form-label" for="nroSiaf">Nro Siaf</label>
                      <input type="text" class="form-control" id="nroSiaf" v-model="nroSiaf" autocomplete="off" placeholder="Ejem: 00000">
                    </div>
                    <div class="mb-3 col-12 col-sm-12 col-md-6">
                      <label class="form-label" for="monto">Importe Total<span>*</span></label>
                      <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected">
                        <input v-model="montoTotal" type="number" placeholder="0.00" step="0.10" readonly class="form-control">
                        <span class="input-group-btn input-group-append">
                          <button class="btn btn-primary bootstrap-touchspin-up" type="button" title="Calcular" @click="calculateHandleClick">
                            <i class="fas fa-dollar-sign"></i>
                          </button>
                        </span>
                      </div>
                    </div>
                    <div class="mb-3 col-12 col-sm-12 col-md-6">
                      <label class="form-label" for="fechaPago">Fecha de pago</label>
                      <input type="date" class="form-control" id="fechaPago" v-model="fechaPago" autocomplete="off">
                    </div>
                    <div class="mb-3 col-12 col-sm-12">
                      <label class="form-label" for="bank_id">Pago a través del banco <i class="text-muted"> - opcional</i> </label>
                      <select id="bank_id" class="form-select" v-model="bank_id">
                        <option value="">Seleccione</option>
                        <option v-for="(bank, index) in banks" :key="index" :value="bank.id">{{ bank.name }}</option>
                      </select>
                    </div>
                    <div class="col-12 col-sm-12 text-end">
                      <button class="btn btn-danger" @click="hideModal">
                        Cancelar
                      </button>
                      <button class="btn btn-primary" @click="createOrUpdate" v-if="!isFormBeingSent">
                        Guardar <i class="far fa-paper-plane"></i>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </template>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="modal-cancel-voucher" tabindex="-1" aria-labelledby="modal-cancel-voucher" aria-modal="true" role="dialog">
      <div class="modal-dialog modal-sm">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title select-none" id="modal-para-registrar-ventas"> Anulación </h5>
            <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close" @click="hideModal"></button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="mb-3 col-12 col-sm-12">
                <label class="form-label" for="pleaForAnnulment">Observación</label>
                <textarea class="form-control rezise-none" style="max-block-size: 170px" rows="4" id="pleaForAnnulment" placeholder="Escribe una descripción..." v-model="pleaForAnnulment"></textarea>
              </div>
            </div>
            <div class="row">
              <div class="mb-3 col-12">
                <input type="checkbox" v-model="isRebilling" id="isRebilling" class="cursor-pointer">
                <label for="isRebilling" class="cursor-pointer user-select-none">Refacturación</label>
              </div>
            </div>
            <div v-if="isRebilling" class="row">
              <div class="mb-3 col-12 col-sm-12">
                <label class="form-label" for="newNroVoucher">Nuevo Comprobante</label>
                <input class="form-control" type="text" id="newNroVoucher" placeholder="Ejem: F000-000" v-model="newNroVoucher">
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button class="btn btn-danger" @click="closeModal('modal-cancel-voucher')"> Cancelar </button>
            <button class="btn btn-primary" @click="annulmentVoucher" v-if="!isFormBeingSent"> Guardar </button>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="modal-calculate-total-import" tabindex="-1" aria-labelledby="modal-calculate-total-import" aria-modal="true" role="dialog">
      <div class="modal-dialog modal-sm">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title select-none" id="modal-para-registrar-ventas"> Calcular </h5>
            <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close" @click="closeModal('modal-calculate-total-import')"></button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-sm-12">
                <table class="table table-sm table-bordered">
                  <tbody>
                    <tr>
                      <th class="text-right calculateTh">Monto</th>
                      <td>
                        <input type="number" step="0.10" class="form-control form-control-sm" @keyup="calculateTotalImport()" v-model="monto" placeholder="0.00">
                      </td>
                    </tr>
                    <tr>
                      <th class="text-right calculateTh">Detracción</th>
                      <td>
                        <input type="number" step="0.10" class="form-control form-control-sm" @keyup="calculateTotalImport()" v-model="detraccion" placeholder="0.00">
                      </td>
                    </tr>
                    <tr>
                      <th class="text-right calculateTh">Retención</th>
                      <td>
                        <input type="number" step="0.10" class="form-control form-control-sm" @keyup="calculateTotalImport()" v-model="retencion" placeholder="0.00">
                      </td>
                    </tr>
                    <tr>
                      <th class="text-right calculateTh">
                        Importe
                      </th>
                      <td>
                        S/ {{ montoTotal | parseCentimos }}
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <div class="modal-footer d-grid">
            <button class="btn btn-primary" @click="finishCalculate">Aceptar y regresar</button>
          </div>
        </div>
      </div>
    </div>

  </div>
</template>

<style src="./SeguimientoComprobanteComponent.css" scoped></style>
