<template>
  <div>
    <LoaderComponent v-if="isLoading"></LoaderComponent>
    <div class="row">
      <div class="col-sm-12 col-md-6" v-if="can(canCreateShopping)">
        <div class="card">
          <div class="card-body">
            <h3 class="mb-0">Compra</h3>
            <hr class="my-2">
            <div class="row">
              <div class="col-12 col-sm-12">
                <div class="form-group">
                  <label for="idEnterpriseMyCompanyShopping">Empresa</label>
                  <select class="form-control" id="idEnterpriseMyCompanyShopping" v-model="idEnterpriseMyCompanyShopping">
                    <option value=""> __Seleccione la empresa__ </option>
                    <option v-for="(enterprise, index) in listEnterprises" :key="index" :value="enterprise.idEnterpriseMyCompany">
                      {{ enterprise.fullName }}
                    </option>
                  </select>
                </div>
              </div>

              <div class="col-12 col-sm-12">
                <div class="form-group">
                  <label for="synchronizeBy">Sincronizar por</label>
                  <select class="form-control" id="synchronizeBy" v-model="synchronizeByShopping">
                    <option :value="null"> __Seleccione una opción__ </option>
                    <option value="document">Comprobante</option>
                    <option value="oneDay">Un día</option>
                    <option value="oneToMoreDays">Rango de fecha</option>
                  </select>
                </div>
              </div>

              <template v-if="synchronizeByShopping == 'document'">
                <div class="col-12 col-sm-12 col-md-6">
                  <div class="form-group">
                    <label for="serieComprobante">Serie comprobante</label>
                    <input type="text" class="form-control" v-model="serieComprobanteShopping" id="serieComprobante">
                  </div>
                </div>
                <div class="col-12 col-sm-12 col-md-6">
                  <div class="form-group">
                    <label for="nroComprobante">Nro comprobante</label>
                    <input type="text" class="form-control" v-model="nroComprobanteShopping" id="nroComprobante">
                  </div>
                </div>
              </template>

              <template v-if="synchronizeByShopping == 'oneDay' || synchronizeByShopping == 'oneToMoreDays'" >
                <div class="col-12 col-sm-12 col-md-6">
                  <div class="form-group">
                    <label for="dateStart">Fecha {{ synchronizeByShopping == 'oneToMoreDays' ? 'de inicio' : '' }} </label>
                    <input type="date" class="form-control" v-model="dateStartShopping" id="dateStart">
                  </div>
                </div>
              </template>

              <template v-if="synchronizeByShopping == 'oneToMoreDays'">
                <div class="col-12 col-sm-12 col-md-6">
                  <div class="form-group">
                    <label for="dateEnd">Fecha Fin </label>
                    <input type="date" class="form-control" v-model="dateEndShopping" id="dateEnd">
                  </div>
                </div>
              </template>
            </div>

            <div class="row">
              <div class="col-sm-12 text-center">
                <button v-if="!isSentForm" class="btn btn-primary" @click="handleSynchronizedShopping">
                  <i class="fas fa-search"></i>
                  Buscar
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-sm-12 col-md-6" v-if="can(canCreateSale)">
        <div class="card">
          <div class="card-body">
            <h3 class="mb-0">Venta</h3>
            <hr class="my-2">
            <div class="row">
              <div class="col-12 col-sm-12">
                <div class="form-group">
                  <label for="synchronizeBy">Sincronización por</label>
                  <select class="form-control" id="synchronizeBy" v-model="synchronizeBySale" readonly>
                    <!-- <option :value="null"> __Seleccione una opción__ </option> -->
                    <option value="document">Comprobante</option>
                  </select>
                </div>
              </div>
              <div class="col-12 col-sm-12">
                <div class="form-group">
                  <label for="idEnterpriseMyCompanySale"> <span v-if="!idEnterpriseMyCompanySale">Seleccione la</span> Empresa</label>
                  <select class="form-control" id="idEnterpriseMyCompanySale" v-model="idEnterpriseMyCompanySale">
                    <option value=""> __Seleccione la empresa__ </option>
                    <option v-for="(enterprise, index) in listEnterprises" :key="index" :value="enterprise.idEnterpriseMyCompany">
                      {{ enterprise.fullName }}
                    </option>
                  </select>
                </div>
              </div>
              <template v-if="idEnterpriseMyCompanySale">
                <div class="col-12 col-sm-12 col-md-6">
                  <div class="form-group">
                    <label for="serieComprobanteSale">Serie comprobante</label>
                    <input type="text" class="form-control" v-model="serieComprobanteSale" id="serieComprobanteSale">
                  </div>
                </div>
                <div class="col-12 col-sm-12 col-md-6">
                  <div class="form-group">
                    <label for="nroComprobanteSale">Nro comprobante</label>
                    <input type="text" class="form-control" v-model="nroComprobanteSale" id="nroComprobanteSale">
                  </div>
                </div>
              </template>
            </div>

            <div class="row justify-content-center">
              <div class="col-sm-12 text-center">
                <button v-if="!isSentForm" class="btn btn-primary" @click="handleSynchronizedSale"><i class="fas fa-search"></i> Buscar</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="modal-sale-list" tabindex="-1" aria-labelledby="modalSaleList" aria-hidden="true">
      <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="listPucharse">Comprobante | {{sale.serie_comprobante}} - {{sale.numero_comprobante}}</h5>
            <button type="button" class="btn-close" @click="closeModal('modal-sale-list')" aria-label="Cerrar"></button>
          </div>
          <form id="form-sale" class="form" action="javascript:;">
            <div class="modal-body" style="font-size: 13px">
              <div class="row">

                <div class="col-sm-12 col-md-4">
                  <div class="row">
                    <div class="col-sm-12 col-md-12">
                      <div class="row">
                        <div class="col-sm-12">
                          <h6>DATOS DEL CLIENTE</h6>
                        </div>
                        <div class="col-sm-12">
                          <div class="client-info-content">
                            <div>Nombre:</div>
                            <div>{{ sale.cliente && sale.cliente.razon_social || '-' }}</div>
                          </div>
                        </div>
                        <div class="col-sm-12">
                          <div class="client-info-content">
                            <div>RUC:</div>
                            <div>{{ sale.cliente && sale.cliente.num_doc || '-' }}</div>
                          </div>
                        </div>
                        <div class="col-sm-12 mb-3">
                          <div class="client-info-content">
                            <div>Dirección:</div>
                            <div>{{ sale.cliente && sale.cliente.direccion_fiscal || '-' }}</div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-12">
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
                              <td class="py-4">{{ sale.fecha_registro | formatDate }} {{ sale.fecha_registro | parseDateHour }}</td>
                              <td class="py-4">{{ sale.condicion_pago && sale.condicion_pago.condicionpago }}</td>
                              <td class="py-4">{{ sale.id_codigomoneda == 'PEN' ? 'Soles' : 'Dólares' }}</td>
                              <td class="py-4">{{ Boolean(sale.nro_guia_remision) ? sale.nro_guia_remision : '' }}</td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                    <div class="col-sm-12 mb-3">
                      <div class="content-state-sunat">
                        <template v-if="sale.estado_envio_sunat">
                          <img :src="renderIconState(sale.estado_envio_sunat)" height="30" :alt="`Imagen de ${sale.estado_envio_sunat}`">
                        </template>
                        <p>
                          Estado de documento en sunat <br> <b class="text-uppercase"> {{ sale.estado_envio_sunat }}</b>
                        </p>
                      </div>
                    </div>
                    <div class="col-sm-12 col-md-6 mb-3">
                      <label class="form-label" style="margin-bottom: 0.3em;">
                        <template v-if="!attachPurchaseOrderDocument">Adjuntar</template>
                        Orden de compra
                      </label>
                      <label for="attachPurchaseOrderDocument" class="input-file" style="position: relative;">
                        <span v-if="!attachPurchaseOrderDocument">
                          <i class="far fa-file-pdf text-color-pdf"></i>Seleccione PDF
                        </span>
                        <span v-else>
                          <i class="fas fa-check-circle text-success"></i> Archivo
                          <button class="btn btn-sm btn-danger"
                            style="position: absolute; top: 0; right: 0"
                            @click="attachPurchaseOrderDocument = null">
                            <i class="far fa-trash-alt m-0"></i>
                          </button>
                        </span>
                      </label>
                      <input type="file"
                        class="form-control d-none"
                        id="attachPurchaseOrderDocument"
                        accept="application/pdf"
                        @change="changeFile('attachPurchaseOrderDocument')">
                    </div>
                    <div class="col-sm-12 col-md-6 mb-3">
                      <label class="form-label" style="margin-bottom: 0.3em;">
                        <template v-if="!attachRemissionGuideDocument">Adjuntar</template>
                        Guía de remisión
                      </label>
                      <label for="attachRemissionGuideDocument" class="input-file" style="position: relative;">
                        <span v-if="!attachRemissionGuideDocument">
                          <i class="far fa-file-pdf text-color-pdf"></i>
                          Seleccione PDF
                        </span>
                        <span v-else>
                          <i class="fas fa-check-circle text-success"></i> Archivo
                          <button class="btn btn-sm btn-danger"
                            style="position: absolute; top: 0; right: 0"
                            @click="attachRemissionGuideDocument = null">
                            <i class="far fa-trash-alt m-0"></i>
                          </button>
                        </span>
                      </label>
                      <input type="file"
                        class="form-control d-none"
                        id="attachRemissionGuideDocument"
                        accept="application/pdf"
                        @change="changeFile('attachRemissionGuideDocument')">
                    </div>

                    <div class="mb-3 col-12 col-sm-12 col-md-6">
                      <label class="form-label" for="codigoUnidadEjecutora">Código Unidad Ejecutora<i class="text-muted"> - Opcional</i> </label>
                      <input type="number" class="form-control" id="codigoUnidadEjecutora" v-model="codigoUnidadEjecutora" autocomplete="off" placeholder="Ejem: 0000">
                    </div>
                    <div class="mb-3 col-12 col-sm-12 col-md-6">
                      <label class="form-label" for="nroSiaf">Nro Siaf<i class="text-muted"> - Opcional</i>  </label>
                      <input type="number" class="form-control" id="nroSiaf" v-model="nroSiaf" autocomplete="off" placeholder="Ejem: 0000">
                    </div>

                    <div class="col-sm-12 col-md-12 mb-3">
                      <label class="form-label" for="descripcionBienServicio">Observación <i class="text-muted"> - Opcional</i></label>
                      <textarea
                        rows="2"
                        class="form-control"
                        id="descripcionBienServicio"
                        placeholder="Escríbe una observación aquí..."
                        v-model="descripcionBienServicio"></textarea>
                    </div>

                    <div class="col-12 mb-1">
                      <h6 class="fw-bold text-primary m-0">CONTACTO</h6>
                      <hr class="m-0">
                    </div>

                    <div class="col-sm-12 col-md-12 mb-2">
                      <label class="form-label" for="contactNroPhone">Número celular</label>
                      <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected">
                        <input type="tel"
                          autocomplete="off"
                          id="contactNroPhone"
                          class="form-control form-control-sm list-inputs-clear text-uppercase"
                          v-model="contact.nroPhone"
                          placeholder="Ejem. 951557314"
                          @keypress.enter="$event.preventDefault()">
                        <span class="input-group-btn input-group-append">
                          <button class="btn btn-sm btn-primary bootstrap-touchspin-up"
                            type="button"
                            @click="searchContactByNroPhone(contact.nroPhone)"
                          >
                            <i v-if="!isSearchContact" class="fas fa-search"></i>
                            <div v-else class="spinner-border text-white" role="status">
                              <span class="visually-hidden">Añadiendo</span>
                            </div>
                          </button>
                        </span>
                      </div>
                    </div>

                    <div class="col-sm-12">
                      <label class="form-label" for="contact.fullName">Nombres</label>
                      <input type="text" autocomplete="off" class="form-control" id="contact.fullName" v-model="contact.fullName" :readonly="contact.contactRegistered" />
                    </div>

                  </div>
                </div>

                <div class="col-sm-12 col-md-8 bl2-primary">
                   <div class="col-sm-12">
                      <h6>LISTA DE PRODUCTOS</h6>
                    </div>
                  <div class="table-responsive">
                    <table class="table table-sm table-hover">
                      <thead>
                        <tr>
                          <th>Cant.</th>
                          <th>Descripción</th>
                          <th>Agregar serie</th>
                          <th>
                            Series
                            <span class="span-serie primary">Existente</span>
                            <span class="span-serie info">Nuevo</span>
                          </th>
                          <th>Licencias</th>
                          <th>Precio</th>
                          <th class="text-right">Importe</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="(detail, index) in saleDetail" :key="index">
                          <td class="xy-center">{{ Number(detail.cantidad) }}</td>
                          <td class="y-center" style="font-size: 11px; min-inline-size: 150px;">{{ detail.descripcion }} </td>
                          <td class="xy-center column-input">
                            <template v-if="!isSerieByAdding">
                              <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected">
                                <input type="text" class="form-control form-control-sm list-inputs-clear text-uppercase" placeholder="Escribe aquí..." @keypress.enter="addSerieToSaleDetail($event, detail, index)">
                                <span class="input-group-btn input-group-append">
                                  <button class="btn btn-sm btn-primary bootstrap-touchspin-up" type="button" @click="addSerieToSaleDetail($event, detail, index)"><i class="fas fa-plus pointer-events-none"></i></button>
                                </span>
                              </div>
                            </template>
                            <template v-else>
                              <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Añadiendo</span>
                              </div>
                              <span>Añadiendo...</span>
                            </template>
                          </td>
                          <td class="y-center">
                            <div class="content-series" v-if="detail.listSeries.length">
                              <span
                                v-for="(serie,indexSerie) in detail.listSeries"
                                :key="indexSerie"
                                :class="`span-serie ${serie.id ? 'primary' : 'info' }`">
                                {{ serie.nroSerie }}
                                <strong class="span-delete" @click="removeSerieToSaleDetail(detail, indexSerie, index)">x</strong>
                              </span>
                            </div>
                            <div class="content-series" v-else>
                              <span>
                                <i class="fas fa-barcode"></i> Sin serie(s)
                              </span>
                            </div>
                          </td>
                          <td>
                            <button class="btn btn-sm btn-primary" @click="openModalLicense(detail, index)">
                              <span v-if="!detail.licenses">
                                Agregar <i class="fas fa-plus"></i>
                              </span>
                              <span v-else> {{ detail.licenses.quantityLicenses }} licencia(s) </span>
                            </button>
                          </td>
                          <td class="y-center price-import">S/ {{ detail.precio | parseCentimos }}</td>
                          <td class="y-center text-right price-import">S/ {{ (Number(detail.importe) + Number(detail.igv)) | parseCentimos }}</td>
                        </tr>
                        <tr>
                          <th colspan="5" class="text-right">Importe total</th>
                          <td class="text-right">S/ {{sale.total}}</td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>

              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-danger" @click="closeModal('modal-sale-list')">Cancelar</button>
              <button type="button" class="btn btn-primary" @click="sendFormListSale">Sincronizar <i class="far fa-paper-plane"></i></button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div class="modal fade" id="my-company-list" tabindex="-1" aria-labelledby="listPucharse" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="listPucharse">Lista de compras</h5>
            <button type="button" class="btn-close" @click="closeModalMyCompanyList" aria-label="Cerrar"></button>
          </div>
          <div class="modal-body max-size">
            <div class="row">
              <div class="col-sm-12 pb-3">
                <label for="">Seleccione el comprador</label>
                <v-select
                  :options="listUsers"
                  :reduce="user => user.id"
                  :getOptionLabel="user => user.name"
                  v-model="idUser"
                  class="pointer-events-none"
                  placeholder="Seleccione el comprador"
                />
              </div>
            </div>
            <div class="table-reponsive">
              <table class="table table-sm table-hover">
                <thead>
                  <tr>
                    <th class="text-center"><i class="far fa-check-square"></i></th>
                    <th>Fecha emisión</th>
                    <th>Proveedor</th>
                    <th>N° factura</th>
                    <th class="text-center">Total</th>
                    <th class="text-center">Detalle</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(shoppingMyCompany, index) in listShoppingMyCompany" :key="index">
                    <td class="text-center input-checked">
                      <input type="checkbox" class="cursor-pointer" @change="handleChangeCheckbox(shoppingMyCompany.id_compra, index)" >
                    </td>
                    <td> {{ shoppingMyCompany.fecha_registro | formatDate }} </td>
                    <td> {{ shoppingMyCompany.proveedor.razon_social }} </td>
                    <td> {{ shoppingMyCompany.serie_comprobante || '0000' }} - {{ shoppingMyCompany.numero_comprobante || '0000' }} </td>
                    <td class="text-right"> <sup>S/</sup> {{ shoppingMyCompany.total | parseCentimos }} </td>
                    <td class="text-center">
                      <button class="btn btn-sm btn-primary" @click="openModalPucharseDetails(shoppingMyCompany.detalle_compra)">
                        <i class="far fa-eye"></i> Ver detalle
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" @click="closeModalMyCompanyList">Cancelar</button>
            <button type="button" class="btn btn-primary" @click="sendFormListShopping">Sincronizar <i class="far fa-paper-plane"></i></button>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="pucharse-detail" tabindex="-1" aria-labelledby="purcharsDetail" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="purcharsDetail">Detalle de compra</h5>
            <button type="button" class="btn-close" @click="closeModalPucharseDetail" aria-label="Cerrar"></button>
          </div>
          <div class="modal-body">
            <div class="row" v-for="(detail, index) in listDetail" :key="index">
              <div class="col-sm-12">
                <div class="content-detail">
                  <form @submit.prevent="addSerieToDetail($event, detail, index)" autocomplete="off">
                    <label>
                      <input type="text" class="form-control form-control-sm input-text-serie" name="serie" placeholder="SERIE">
                    </label>
                    <button type="submit" class="btn btn-sm btn-info"><i class="far fa-plus-square"></i></button>
                  </form>
                  <div class="table-reponsive">
                    <table class="table table-sm table-hover">
                      <thead>
                        <tr>
                          <th>Cant.</th>
                          <th>Descripción</th>
                          <th>Precio</th>
                          <th>Importe</th>
                          <th class="text-center">Series</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>{{ Number(detail.cantidad) }}</td>
                          <td>{{ detail.descripcion }}</td>
                          <td>{{ detail.precio }}</td>
                          <td>{{ Number(detail.importe) + Number(detail.igv) }}</td>
                          <td>
                            <div class="content-series">
                              <span class="span-serie primary" v-for="(serie,index) in detail.listSeries" :key="index">
                                {{ serie.name }}
                                <strong class="span-delete" @click="removeSerie(serie, index, detail.listSeries)">x</strong>
                              </span>
                            </div>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" @click="closeModalPucharseDetail">Regresar <i class="fas fa-undo"></i></button>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="list-details" tabindex="-1" aria-labelledby="listDetails" aria-hidden="true">
      <div class="modal-dialog modal-sm">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Lista de licencias</h5>
            <button type="button" class="btn-close" @click="closeModalLicenses" aria-label="Cerrar"></button>
          </div>
          <div class="modal-body">
            <div class="col-sm-12 mb-3">
              <label class="form-label" for="installationDate">Descripción</label>
              <textarea class="form-control resize-none" rows="3" v-model="licenses.description" placeholder="Escríbe alguna descripción..."></textarea>
            </div>
            <div class="col-sm-12 mb-3">
              <label class="form-label" for="quantityLicenses">Cantidad de licencias</label>
              <input type="number"
                class="form-control"
                id="quantityLicenses"
                v-model="licenses.quantityLicenses">
            </div>
            <div class="col-sm-12">
              <label class="form-label" for="installationDate">Fecha instalación</label>
              <input type="date"
                class="form-control"
                :min="currentDay"
                id="installationDate"
                v-model="licenses.installationDate">
            </div>
          </div>
          <div class="modal-footer">

            <button type="button" class="btn btn-danger" @click="closeModalLicenses"> Cancelar </button>
            <button v-if="resourceAddLicense.detail && resourceAddLicense.detail.licenses"
              type="button" class="btn btn-info" @click="removeLicense">
              Quitar
            </button>
            <button type="button" class="btn btn-primary" @click="addLicense"> Guardar <i class="far fa-paper-plane"></i> </button>
          </div>
        </div>
      </div>
    </div>

  </div>
</template>

<style src="./SynchronizationComponent.css" scoped></style>
<script src="./SynchronizationComponent.js"></script>

