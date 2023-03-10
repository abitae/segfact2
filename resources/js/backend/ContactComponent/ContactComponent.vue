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
                <button
                  v-if="can(canCreate)"
                  class="btn btn-primary"
                  @click="openModalCreateCustomer">
                  <i class="fas fa-plus"></i> Nuevo
                </button>
              </div>
              <form @submit.prevent="getListContacts()" class="row">
                <div class="mb-3 col-12 col-md-6 col-lg-3 col-xl-2">
                  <label class="form-label" for="nroDocumentSearch">RUC/DNI <i class="fas fa-question-circle" title="Presione la tecla ENTER para buscar"></i></label>
                  <input class="form-control" type="text"
                    v-model="nroDocumentSearch"
                    id="nroDocumentSearch"
                    placeholder="Escríbe aquí..."
                    autocomplete="off">
                </div>
                <div class="mb-3 col-12 col-md-6 col-lg-3 col-xl-2">
                  <label class="form-label" for="fullNameSearch">Razón Social/Nombres <i class="fas fa-question-circle" title="Presione la tecla ENTER para buscar"></i></label>
                  <input class="form-control" type="text"
                    v-model="fullNameSearch"
                    id="fullNameSearch"
                    placeholder="Escríbe aquí..."
                    autocomplete="off">
                </div>
                <div class="mb-3 col-12 col-md-6 col-lg-3 col-xl-2">
                  <label class="form-label" for="addressSearch">Dirección <i class="fas fa-question-circle" title="Presione la tecla ENTER para buscar"></i></label>
                  <input class="form-control" type="text"
                    v-model="addressSearch"
                    id="addressSearch"
                    placeholder="Escríbe aquí..."
                    autocomplete="off">
                </div>
                <div class="mb-3 col-12 col-md-6 col-lg-3 col-xl-2">
                  <label class="form-label" for="emailSearch">Correo electrónico <i class="fas fa-question-circle" title="Presione la tecla ENTER para buscar"></i></label>
                  <input class="form-control" type="text"
                    v-model="emailSearch"
                    id="emailSearch"
                    placeholder="Escríbe aquí..."
                    autocomplete="off">
                </div>
                <input class="d-none" type="submit">
              </form>
              <div v-if="listContacts.total > 0 && can(canView)" class="col-12">
                <div class="table-responsive">
                  <table class="table table-sm table-hover table-bordered">
                    <thead class="thead-primary">
                      <tr>
                        <th class="options" v-if="can(canEdit, canUpdateStatus)">Opciones</th>
                        <th class="status">Estado</th>
                        <th class="text-center">DNI/RUC</th>
                        <th class="column-fullName">Nombres / Razón Social</th>
                        <th class="column-email">Correo Electrónico</th>
                        <th class="column-norPhone text-center">N° Contacto</th>
                        <th class="column-address">Dirección</th>
                      </tr>
                    </thead>
                    <tbody class="vertical-align-middle">
                      <tr v-for="(customer,index) in listContacts.data" :key="index">
                        <td class="options" v-if="can(canEdit, canUpdateStatus)">
                          <button
                            v-if="can(canEdit)"
                            class="btn btn-sm btn-primary"
                            @click="openModalEditContact(customer)">
                            <i class="far fa-edit"></i>
                          </button>
                          <button
                            v-if="can(canUpdateStatus)"
                            @click="updataStatus(customer)"
                            :class="`btn btn-sm btn-${ customer.is_active ? 'success' : 'danger' }`">
                            <i v-if="customer.is_active" class="far fa-check-circle"></i>
                            <i v-else class="far fa-times-circle"></i>
                          </button>
                        </td>
                        <td class="status">
                          <span v-if="customer.is_active" class="badge rounded-pill bg-success registration-status">Activo</span>
                          <span v-else class="badge rounded-pill bg-danger registration-status">Desactivado</span>
                        </td>
                        <td class="text-center cursor-default user-select-all"> <span class="cursor-pointer" @click="copiedAtClipboard(customer.nroDocument)">{{ customer.nroDocument }}</span> </td>
                        <td>{{ customer.fullName }}</td>
                        <td> <a :href="`mailto:${customer.email}`">{{ customer.email }}</a> </td>
                        <td class="text-center"> <a :href="`tel:${customer.nroPhone}`">{{ customer.nroPhone }}</a> </td>
                        <td>{{ customer.address }}</td>
                      </tr>
                    </tbody>
                  </table>
                  <pagination class="select-none"
                    :limit="5"
                    size="small"
                    :data="listContacts"
                    @pagination-change-page="getListContacts"
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

    <div class="modal fade" id="modal-contact" tabindex="-1" aria-labelledby="Modal para registrar contacto" aria-modal="true" role="dialog">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title select-none" id="Modal para registrar contacto"> {{ modalTitle }} Cliente </h5>
            <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close" @click="hideModal"></button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="mb-3 col-12 col-md-6 col-lg-4">
                <label class="form-label" for="typeDocument">Tipo Documento<span>*</span></label>
                <select class="form-select" v-model="typeDocument" id="typeDocument" @change="selectTypeDocumentHandleChange">
                  <option value=""> __Seleccione__ </option>
                  <option value="01">DNI</option>
                  <option value="06">RUC</option>
                </select>
              </div>
              <template v-if="typeDocument">
                <div class="mb-3 col-12 col-md-6 col-lg-4">
                  <label class="form-label">Nro de {{ showTextAccordingDocumentType(typeDocument) }}</label>
                  <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected">
                    <input type="text" v-model="nroDocument" class="form-control" :placeholder="`N° de ${showTextAccordingDocumentType(typeDocument)}`" @keypress.enter="searchDocument">
                    <span class="input-group-btn input-group-append">
                      <button class="btn btn-primary bootstrap-touchspin-up" type="button" @click="searchDocument">
                        <i v-if="!isSearchDocumentSunat" class="fas fa-search"></i>
                        <div v-else class="spinner-border text-white" role="status">
                          <span class="sr-only">Cargando...</span>
                        </div>
                      </button>
                    </span>
                  </div>
                </div>
              </template>
            </div>
            <div class="row" v-if="typeDocument">
              <template v-if="typeDocument == '01'">
                <div class="mb-3 col-12 col-md-6">
                  <label class="form-label" for="name">Nombres<span>*</span></label>
                  <input v-model="name" class="form-control" type="text" id="name" placeholder="Complete sus nombre." autocomplete="off">
                </div>
                <div class="mb-3 col-12 col-md-6">
                  <label class="form-label" for="lastName">Apellidos<span>*</span></label>
                  <input v-model="lastName" class="form-control" type="text" id="lastName" placeholder="Complete sus apellidos." autocomplete="off">
                </div>
              </template>
              <template v-if="typeDocument == '06'">
                <div class="mb-3 col-12 col-sm-12">
                  <label class="form-label" for="fullName">Razón Social/Nombres<span>*</span></label>
                  <input v-model="fullName" class="form-control" type="text" id="fullName" placeholder="Complete su Razón Social / Nombres." autocomplete="off">
                </div>
              </template>
              <div class="mb-3 col-12">
                <label class="form-label" for="address">Dirección<span>*</span></label>
                <input v-model="address" class="form-control" type="text" id="address" placeholder="Complete su dirección." autocomplete="off">
              </div>
              <div class="mb-3 col-12 col-md-6 col-lg-8">
                <label class="form-label" for="email">Correo Electrónico<span>*</span></label>
                <input v-model="email" class="form-control" type="email" id="email" placeholder="Complete su correo electrónico." autocomplete="off">
              </div>
              <div class="mb-3 col-12 col-md-6 col-lg-4">
                <label class="form-label" for="nroPhone">Nro de Teléfono/Celular<span>*</span></label>
                <input v-model="nroPhone" class="form-control" type="tel" id="nroPhone" placeholder="Complete su Telefono/Celular." autocomplete="off">
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button class="btn btn-danger" @click="hideModal">Cancelar</button>
            <button class="btn btn-primary" @click="createOrUpdate">Guardar</button>
          </div>
        </div>
      </div>
    </div>

  </div>
</template>

<script src="./ContactComponent.js"></script>

<style scoped>
  .column-fullName,
  .column-address {
    min-width: 200px;
  }
  .column-email {
    min-width: 150px;
  }

  .column-norPhone {
    min-width: 100px;
  }
</style>
