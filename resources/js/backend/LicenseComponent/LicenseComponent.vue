<template>
  <div>
    <div class="row">
      <div class="col-12 col-sm-12">
        <div class="card">
          <div class="card-body">
            <div class="col-12 text-center">
              <h5 class="fw-bold">ESTADOS DE EXPIRACIÓN EN LICENCIAS</h5>
            </div>
            <div class="col-sm-12 text-center">
              <div class="color-description bg-toinstall" >
                <p> <i class="far fa-square icon-color-toinstall"></i> Por instalar </p>
              </div>
              <div class="color-description bg-installed" >
                <p> <i class="fas fa-square icon-color-installed"></i> Instalados </p>
              </div>
              <div class="color-description bg-byExpire" >
                <p> <i class="fas fa-square icon-color-byExpire"></i> Por expirar </p>
              </div>
              <div class="color-description bg-expired" >
                <p> <i class="fas fa-square icon-color-expired"></i> Expirados </p>
              </div>
            </div>
            <hr>
            <div style="min-height: 300px;">
              <div class="card-title select-none title-with-button">
                <h5 class="color-customized"><i class="fas fa-search"></i> Filtros de búsqueda</h5>
                <button class="btn btn-primary" @click="openModalCreate" v-if="can(canCreate)">
                  <i class="fas fa-plus"></i> Nuevo
                </button>
              </div>

              <form class="row" action="javascript:;" @submit.prevent="getListLicenses()" autocomplete="off">
                <div class="mb-3 col-12 col-md-6 col-lg-3 col-xl-2">
                  <label class="form-label" for="nameClientSearch">Nombre producto <i class="fas fa-question-circle" title="Seleccione una opción para buscar"></i></label>
                  <input type="text" class="form-control" placeholder="Buscar..." v-model="nameProductSearch" id="nameClientSearch">
                </div>
                <div class="mb-3 col-12 col-md-6 col-lg-3 col-xl-2">
                  <label class="form-label" for="nameClientSearch">Nombre cliente <i class="fas fa-question-circle" title="Seleccione una opción para buscar"></i></label>
                  <input type="text" class="form-control" placeholder="Buscar..." v-model="nameClientSearch" id="nameClientSearch">
                </div>
                <div class="mb-3 col-12 col-md-6 col-lg-3 col-xl-2">
                  <label class="form-label" for="nameContactSearch">Nombre contacto <i class="fas fa-question-circle" title="Seleccione una opción para buscar"></i></label>
                  <input type="text" class="form-control" placeholder="Buscar..." v-model="nameContactSearch" id="nameContactSearch">
                </div>

                <div class="mb-3 col-12 col-md-6 col-lg-3 col-xl-2">
                  <label class="form-label" for="searchByDate">Buscar por fecha <i class="fas fa-question-circle" title="Seleccione una opción para buscar"></i></label>
                  <select
                    id="searchByDate"
                    name="searchByDate"
                    v-model="searchByDate"
                    class="form-select"
                    @change="getListLicenses()">
                    <option value="" selected>Todos</option>
                    <option value="installationDate">Instalación</option>
                    <option value="expirationDate">Expiración</option>
                  </select>
                </div>

                <template v-if="searchByDate">
                  <div class="mb-3 col-12 col-md-6 col-lg-3 col-xl-2">
                    <label class="form-label" for="dateStartSearch">Fecha Inicio <i class="fas fa-question-circle" title="Seleccione una fecha para buscar"></i></label>
                    <input
                      type="date"
                      class="form-control"
                      id="dateStartSearch"
                      v-model="dateStartSearch" @change="getListLicenses()">
                  </div>
                  <div class="mb-3 col-12 col-md-6 col-lg-3 col-xl-2">
                    <label class="form-label" for="dateEndSearch">Fecha Fin <i class="fas fa-question-circle" title="Seleccione una fecha para buscar"></i></label>
                    <input
                      type="date"
                      class="form-control"
                      id="dateEndSearch"
                      v-model="dateEndSearch" @change="getListLicenses()">
                  </div>
                </template>
                <input type="submit" class="d-none">
              </form>

              <div v-if="listLicenses.total && can(canView)" class="col-12">
                <div class="table-responsive">
                  <table class="table table-sm table-hover table-bordered">
                    <thead class="thead-primary">
                      <tr>
                        <th rowspan="2" class="options" v-if="can(canEdit, canUpdateStatus)">Opciones</th>
                        <!-- <th rowspan="2" class="status">Estado</th> -->
                        <th rowspan="2">Producto</th>
                        <th colspan="2" class="text-center">Fecha</th>
                        <th rowspan="2" class="text-center">Cliente</th>
                        <th rowspan="2" class="text-center">Contacto</th>
                        <th rowspan="2" class="text-center">Cantidad</th>
                        <th rowspan="2" class="text-center">Descripción</th>
                      </tr>
                      <tr>
                        <th class="text-center">Instalación</th>
                        <th class="text-center">Expiración</th>
                      </tr>
                    </thead>
                    <tbody class="vertical-align-middle">
                      <tr v-for="(license,index) in listLicenses.data" :key="index"
                      :class="setBackgoundColor(license.expirationDate, license.installationDate)">
                        <td class="options" v-if="can(canEdit, canUpdateStatus)">
                          <button
                            v-if="can(canEdit)"
                            @click="openModalEdit(license)"
                            class="btn btn-sm btn-primary"
                          > <i class="far fa-edit"></i>
                          </button>
                          <button
                            v-if="can(canUpdateStatus)"
                            @click="updataStatus(license)"
                            :class="`btn btn-sm btn-${ license.is_active ? 'success' : 'danger' }`">
                            <i v-if="license.is_active" class="far fa-check-circle"></i>
                            <i v-else class="far fa-times-circle"></i>
                          </button>
                        </td>
                        <!-- <td class="status">
                          <span v-if="license.is_active" class="badge rounded-pill bg-success registration-status">Activo</span>
                          <span v-else class="badge rounded-pill bg-danger registration-status">Desactivado</span>
                        </td> -->
                        <td class="text-left"> {{ license.product}} </td>
                        <td class="text-capitalize text-center"> {{ license.installationDate | formatDate }} </td>
                        <td class="text-center"> {{ license.expirationDate | formatDate }} </td>
                        <td> {{ license.client.fullName}} </td>
                        <td> {{ license.contact.fullName}} </td>
                        <td class="text-center"> {{ license.quantity }} </td>
                        <td class="column-description"> {{ license.description }} </td>
                      </tr>
                    </tbody>
                  </table>
                  <pagination class="select-none"
                    :limit="5"
                    size="small"
                    :data="listLicenses"
                    @pagination-change-page="getListLicenses"
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

    <div class="modal fade" id="modal-license" tabindex="-1" aria-labelledby="Modal para licencias" aria-modal="true" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title select-none" id="Modal para licencias"> {{ modalTitle }} seguimiento de licencia</h5>
            <button type="button"
              class="btn-close"
              aria-label="Cerrar"
              @click="closeModal"
            ></button>
          </div>
          <div class="modal-body">
            <div class="row">

              <div class="mb-3 col-12 col-sm-12 col-md-6">
                <label class="form-label" for="product">Producto<span>*</span></label>
                <input type="text" class="form-control" id="product" v-model="product">
              </div>
              <div class="mb-3 col-12 col-sm-12 col-md-6">
                <label class="form-label" for="quantity">Cantidad de licencias<span>*</span></label>
                <input type="number" min="1" step="1" class="form-control" id="quantity" v-model="quantity" autocomplete="off">
              </div>

              <div class="mb-3 col-12 col-sm-12 col-md-12">
                <label class="form-label" for="description">Descripción<span>*</span> </label>
                <textarea id="description" v-model="description" class="form-control textarea-height"></textarea>
              </div>

              <div class="mb-3 col-12 col-sm-12 col-md-6">
                <label class="form-label" for="fechaEmision">Fecha de instalación<span>*</span></label>
                <input type="date" class="form-control" id="fechaEmision" :min="dateInitWorking" v-model="installationDate">
              </div>

              <div class="mb-3 col-12 col-sm-12 col-md-6">
                <label class="form-label" for="fechaEmision">Fecha de vencimiento<span>*</span></label>
                <input type="date" class="form-control" id="fechaEmision" :min="dateInitWorking" v-model="expirationDate">
              </div>

              <div class="mb-3 col-12 col-sm-12">
                <label class="form-label" for="idClient">Cliente<span>*</span> </label>
                <v-select
                  :class="`form-control ${id ? 'pointer-events-none' : ''}`"
                  style="padding: 0; border-color: transparent"
                  :options="listClients"
                  :getOptionLabel="client => client.fullName"
                  v-model="idClient"
                  :reduce="client => client.id"
                  placeholder="Escribe nombre del cliente"
                  >
                  <div slot="no-options">No hay resultados para mostrar.</div>
                </v-select>
              </div>
              <div class="col-12 col-sm-12">
                <label class="form-label" for="idContacto">Contacto<span>*</span> </label>
                <v-select
                  :class="`form-control ${id ? 'pointer-events-none' : ''}`"
                  style="padding: 0; border-color: transparent"
                  :options="listContacts"
                  :getOptionLabel="contact => contact.fullName"
                  v-model="idContact"
                  :reduce="contact => contact.id"
                  placeholder="Escribe nombre del contacto"
                  >
                  <div slot="no-options">No hay resultados para mostrar.</div>
                </v-select>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button class="btn btn-danger" @click="closeModal">Cancelar</button>
            <button class="btn btn-primary" v-if="!isFormBeingSent" @click="createOrUpdate">Guardar</button>

          </div>
        </div>
      </div>
    </div>

  </div>
</template>

<style type="text/css" src="./LicenseComponent.css"></style>
<script src="./LicenseComponent.js"></script>
