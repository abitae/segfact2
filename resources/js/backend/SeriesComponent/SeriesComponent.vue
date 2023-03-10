<template>
  <div>
    <LoaderComponent v-if="isLoading"></LoaderComponent>
    <div class="row" v-if="can(canView)">
      <div class="col-12 col-sm-12 col-md-6 col-lg-4">
        <div class="card">
          <div class="card-body">
            <div style="min-height: 300px;">
              <div class="card-title select-none">
                <h5 class="color-customized"><i class="fas fa-search"></i> Filtros de búsqueda</h5>

                <!-- <button
                  v-if="can(canCreate)"
                  class="btn btn-primary" @click="openModalCreate">
                  <i class="fas fa-plus"></i> Nuevo
                </button> -->
              </div>

              <form @submit.prevent="getListSeries()" class="row">
                <div class="mb-3 col-12 col-md-12 col-lg-9 col-xl-6">
                  <label class="form-label" for="nroSerieSearch">N° de serie <i class="fas fa-question-circle" title="Presione la tecla ENTER para buscar"></i></label>
                  <input class="form-control text-uppercase" type="text" v-model="nroSerieSearch" id="nroSerieSearch" placeholder="Escríbe aquí..." autocomplete="off">
                </div>
                <input class="d-none" type="submit">
              </form>

              <template v-if="listSeries.total">
                <div class="table-responsive">
                  <table class="table table-sm table-hover">
                    <thead class="thead-primary">
                      <tr>
                        <th class="options" v-if="can(canEdit, canUpdateStatus)">Opciones</th>
                        <th class="status">Estado</th>
                        <th>N° de serie</th>
                      </tr>
                    </thead>
                    <tbody class="vertical-align-middle">
                      <tr v-for="(serie, index) in listSeries.data" :key="index">
                        <td class="options" v-if="can(canEdit, canUpdateStatus)">
                          <button :class="`btn btn-sm btn-primary`" v-if="false">
                            <i class="far fa-edit"></i>
                          </button>
                          <button
                            v-if="can(canUpdateStatus)"
                            :class="`btn btn-sm btn-${serie.is_active ? 'success' : 'danger' }`" @click="updataStatus(serie)">
                            <i v-if="serie.is_active" class="far fa-check-circle"></i>
                            <i v-else class="far fa-times-circle"></i>
                          </button>
                        </td>
                        <td class="status">
                          <span v-if="serie.is_active" class="badge rounded-pill bg-success registration-status">Activo</span>
                          <span v-else class="badge rounded-pill bg-danger registration-status">Desactivado</span>
                        </td>
                        <td>{{serie.nroSerie}}</td>
                      </tr>
                    </tbody>
                  </table>
                  <pagination class="select-none"
                    :limit="5"
                    size="small"
                    :data="listSeries"
                    @pagination-change-page="getListSeries"
                  >
                    <span slot="prev-nav"><i class="fas fa-arrow-left"></i></span>
                    <span slot="next-nav"><i class="fas fa-arrow-right"></i></span>
                  </pagination>
                </div>
              </template>
              <template v-else>
                <EmptyComponent></EmptyComponent>
              </template>
            </div>
          </div>
        </div>
      </div>

      <div class="col-12 col-sm-12 col-md-6 col-lg-8">
        <div class="card">
          <div class="card-body">
            <div style="min-block-size: 150px;">
              <div class="card-title select-none title-with-button">
                <h5 class="color-customized"><i class="fas fa-search"></i> Seguimiento de serie</h5>
                <button v-if="Object.keys(seguimientoSerie).length" class="btn btn-pdf">
                  <i class="far fa-file-pdf"></i> Imprimir
                </button>
              </div>

              <form @submit.prevent="getAdvancedSearch" class="row" autocomplete="off">
                <div class="col-sm-12 col-md-4 col-lg-3 mb-3">
                  <label for="nroSerieAdvancedSearch" class="form-label">N° de serie <i class="fas fa-question-circle" title="Presione la tecla ENTER para buscar"></i></label>
                  <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected">
                    <input data-toggle="touchspin" type="text" placeholder="Buscar..." class="form-control" v-model="nroSerieSearchAdvanced" id="nroSerieAdvancedSearch">
                    <span class="input-group-btn input-group-append">
                      <button class="btn btn-primary bootstrap-touchspin-up" type="submit"><i class="fas fa-search"></i></button>
                    </span>
                  </div>
                </div>
              </form>
              <div v-if="Object.keys(seguimientoSerie).length" class="row monitoring-serie">
                <div v-if="seguimientoSerie.shopping" class="col-12 col-sm-12 col-md-6">
                  <div class="row-information">
                    <h5 class="text-primary">REGISTRO DE COMPRA</h5>
                    <hr class="mt-0">
                    <span class="c-pl">
                      <b>N° Factura</b>
                      <span class="c-pl">
                        {{ seguimientoSerie.shopping.codigoFactura }}
                      </span>
                    </span>
                    <span class="c-pl">
                      <b>Proveedor</b>

                      <span class="c-pl">
                        {{ seguimientoSerie.shopping.proveedor.nroDocument }} - {{ seguimientoSerie.shopping.proveedor.fullName }}
                      </span>
                    </span>
                    <span class="c-pl">
                      <b>Tipo Comprobante</b>
                      <span class="c-pl">
                        {{ seguimientoSerie.shopping.tipo_comprobante.nombre }}
                      </span>
                    </span>
                    <span class="c-pl">
                      <b>Compra en Soles/Dólares</b>
                      <span class="c-pl">
                        {{ seguimientoSerie.shopping.compraSolesDolares == 'PEN' ? 'Soles' : 'Dólares'  }}
                      </span>
                    </span>
                    <span class="c-pl" v-if="seguimientoSerie.shopping.monto">
                      <b>M. Dólares</b>
                      <span class="c-pl">
                        {{ seguimientoSerie.shopping.monto }}
                      </span>
                    </span>
                    <span class="c-pl" v-if="seguimientoSerie.shopping.tipoDeCambio">
                      <b>Tipo cambio</b>
                      <span class="c-pl">
                        {{ seguimientoSerie.shopping.tipoDeCambio }}
                      </span>
                    </span>
                    <span class="c-pl">
                      <b>Sub Total</b>
                      <span class="c-pl">
                        S/ {{ seguimientoSerie.shopping.montoVentaSoles }}
                      </span>
                    </span>
                    <span class="c-pl">
                      <b>IGV</b>
                      <span class="c-pl">
                        S/ {{ seguimientoSerie.shopping.igv }}
                      </span>
                    </span>
                    <span class="c-pl">
                      <b>Monto total</b>
                      <span class="c-pl">
                        S/ {{ seguimientoSerie.shopping.montoTotal }}
                      </span>
                    </span>
                    <span class="c-pl">
                      <b>Fecha emisión</b>
                      <span class="c-pl">
                        {{ seguimientoSerie.shopping.fechaEmision | formatDate }}
                      </span>
                    </span>
                  </div>
                </div>

                <div v-if="seguimientoSerie.sale" class="col-12 col-sm-12 col-md-6">
                  <div class="row-information">
                    <h5 class="text-primary">REGISTRO DE VENTA</h5>
                    <hr class="mt-0">
                    <span class="c-pl">
                      <b>Factura </b>
                      <span class="c-pl"> {{ seguimientoSerie.sale.codigoFactura }}</span>
                    </span>

                    <span class="c-pl">
                      <b> Cliente </b>
                      <span class="c-pl"> {{ seguimientoSerie.sale.cliente.nroDocument }} - {{ seguimientoSerie.sale.cliente.fullName }}</span>
                    </span>

                    <span class="c-pl">
                      <b> Vendedor </b>
                      <span class="c-pl"> {{ seguimientoSerie.sale.vendedor.name }}</span>
                    </span>

                    <span class="c-pl">
                      <b> Tipo Comprobante </b>
                      <span class="c-pl"> {{ seguimientoSerie.sale.tipo_comprobante.nombre }}</span>
                    </span>

                    <span class="c-pl">
                      <b> Compra </b>
                      <span class="c-pl"> {{ seguimientoSerie.sale.compraSolesDolares == 'PEN' ? 'Soles' : 'Dólares'  }}</span>
                    </span>

                    <span class="c-pl" v-if="seguimientoSerie.sale.monto">
                      <b> M. Dólares </b>
                      <span class="c-pl"> {{ seguimientoSerie.sale.monto }}</span>
                    </span>

                    <span class="c-pl" v-if="seguimientoSerie.sale.tipoDeCambio">
                      <b> Tipo cambio </b>
                      <span class="c-pl"> {{ seguimientoSerie.sale.tipoDeCambio }}</span>
                    </span>

                    <span class="c-pl">
                      <b> Sub total </b>
                      <span class="c-pl"> S/ {{ seguimientoSerie.sale.montoVentaSoles }}</span>
                    </span>

                    <span class="c-pl">
                      <b> IGV </b>
                      <span class="c-pl"> S/ {{ seguimientoSerie.sale.igv }}</span>
                    </span>

                    <span class="c-pl">
                      <b> Monto total </b>
                      <span class="c-pl"> S/ {{ seguimientoSerie.sale.montoTotal }}</span>
                    </span>

                    <span class="c-pl">
                      <b> Comisión Venta </b>
                      <span class="c-pl"> S/ {{ seguimientoSerie.sale.saleCommission }}</span>
                    </span>

                    <span class="c-pl">
                      <b> Comisión Contacto </b>
                      <span class="c-pl"> S/ {{ seguimientoSerie.sale.contactCommission }}</span>
                    </span>

                    <span class="c-pl">
                      <b> Fecha emisión </b>
                      <span class="c-pl"> {{ seguimientoSerie.sale.fechaEmision | formatDate }}</span>
                    </span>

                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="modal-series" tabindex="-1" aria-labelledby="myLargeModalLabel" aria-modal="true" role="dialog">
      <div class="modal-dialog modal-sm">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title select-none" id="myLargeModalLabel"> {{ modalTitle }} Serie </h5>
            <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close" @click="closeModal"></button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="mb-3">
                <label class="form-label" for="nroSerie">N° Serie<span>*</span></label>
                <input class="form-control text-uppercase" type="text" id="nroSerie" v-model="nroSerie" placeholder="Ejem: SERIE-001" autocomplete="off">
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button class="btn btn-danger" @click="closeModal">Cancelar</button>
            <button class="btn btn-primary" @click="createOrUpdate" v-if="!isFormSent">Guardar</button>
          </div>
        </div>
      </div>
    </div>

  </div>
</template>

<style scoped>
  .monitoring-serie h5 {
    margin-bottom: 0 !important;
  }
  .row-information {
    margin-block-end: .7rem;
  }
  .c-pl {
    padding-inline-start: 2em;
    display: block;
    line-height: 1.2;
    margin-block-end: 5px;
  }
  hr {
    background-color: #1B82EC;
    block-size: 1px;
    opacity: 1;
    margin-inline-end: 3em;
  }
</style>
<script src="./SeriesComponent.js"></script>
