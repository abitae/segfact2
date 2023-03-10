
<template>
  <div>
    <!-- <InProcessComponent></InProcessComponent> -->
    <LoaderComponent v-if="isLoading"></LoaderComponent>
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <div style="min-height: 300px;">
              <div class="card-title select-none title-with-button">
                <h5 class="color-customized"><i class="fas fa-search"></i> Filtros</h5>
                  <!-- v-if="can(canCreate)" -->
                <button
                  v-if="can(canCreate)"
                  class="btn btn-primary"
                  @click="openModal">
                  <i class="fas fa-plus"></i>
                  <span class="d-none d-md-inline-block">Nuevo</span>
                </button>
              </div>
              <div v-if="can(canView)" class="row">
                <div class="mb-3 col-12 col-md-6 col-lg-3 col-xl-2">
                  <label class="form-label" for="search.name">Nombre <i class="fas fa-question-circle" title="Presione la tecla ENTER para buscar"></i></label>
                    <div class="input-group">
                      <input
                        type="search"
                        v-model="search.name"
                        id="search.name"
                        placeholder="Buscar..."
                        autocomplete="off"
                        class="form-control"
                        @keypress.enter="getBanks"
                      >
                      <span class="input-group-btn input-group-append">
                        <button class="btn btn-primary" type="button" @click="getBanks">
                          <i class="fas fa-search"></i>
                        </button>
                      </span>
                    </div>
                </div>
              </div>

              <!-- && can(canView) -->
              <div v-if="banks.length > 0 && can(canView)" class="col-12">
                <div class="col-12">
                  <div class="table-responsive">
                    <table class="table table-sm table-striped table-bordered">
                      <thead class="thead-primary">
                        <tr>
                          <!-- v-if="can(canEdit, canUpdateStatus)" -->
                          <th class="options" v-if="can(canEdit, canUpdateStatus)">Opciones</th>
                          <th>Estado</th>
                          <th>Nombre</th>
                          <th class="column-date">Creado en</th>
                          <th class="column-date">Actualizado en</th>
                        </tr>
                      </thead>
                      <tbody class="vertical-align-middle">
                        <tr v-for="(bank,index) in banks" :key="index">
                          <!-- v-if="can(canEdit, canUpdateStatus)" -->

                          <td class="options"
                            v-if="can(canEdit, canUpdateStatus)"
                            >
                            <button
                               v-if="can(canEdit)"
                              class="btn btn-sm btn-primary"
                              @click="openModalEdit(bank)"
                            >
                              <!-- v-if="can(canEdit)"
                              -->
                              <i class="far fa-edit"></i>
                            </button>
                            <button
                               v-if="can(canUpdateStatus)"
                              :class="`btn btn-sm btn-${ bank.is_active ? 'success' : 'danger' }`"
                              @click="updataStatus(bank)"
                            >
                              <!-- v-if="can(canUpdateStatus)" -->
                              <i v-if="bank.is_active" class="far fa-check-circle"></i>
                              <i v-else class="far fa-times-circle"></i>
                            </button>
                          </td>
                          <td class="status">
                            <span v-if="bank.is_active" class="badge rounded-pill bg-success registration-status">Activo</span>
                            <span v-else class="badge rounded-pill bg-danger registration-status">Desactivado</span>
                          </td>
                          <td>{{ bank.name }}</td>
                          <td class="column-date">{{ bank.created_at | parseDate}}</td>
                          <td class="column-date">{{ bank.updated_at | parseDate}}</td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
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

    <div class="modal fade" id="modal-bank" tabindex="-1" aria-labelledby="Modal de banco" aria-modal="true" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title select-none" id="Modal de banco"> {{ id ? 'Editar' : 'Crear' }} Banco </h5>
            <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close" @click="hideModal"></button>
          </div>
          <div class="modal-body">
            <div class="form-group col-12">
              <label class="form-label" for="name">Nombres</label>
              <input v-model="name" class="form-control" type="text" id="name" placeholder="Ejem: Banco de la Nación" autocomplete="off">
            </div>
          </div>
          <div class="modal-footer">
            <button class="btn btn-danger" @click="hideModal">Cancelar</button>
            <button class="btn btn-primary" @click="sendForm">Guardar <i class="far fa-paper-plane"></i></button>
          </div>
        </div>
      </div>
    </div>

  </div>
</template>

<style src="./styles.css"></style>
<script type="text/javascript" src="./script.js"></script>
