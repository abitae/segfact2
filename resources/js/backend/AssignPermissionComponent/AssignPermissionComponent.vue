<template>
  <div>
    <div class="row">
      <div class="col-sm-12">
        <div class="card">
          <div class="card-body">
            <div class="card-title select-none">
              <h5 class="color-customized"><i class="fas fa-users"></i> Lista de usuarios</h5>
            </div>
            <form @submit.prevent="getListUsers()" class="row">
              <div class="mb-3 col-12 col-md-6">
                <label class="form-label" for="nameSearch">Nombres <i class="fas fa-question-circle" title="Presione la tecla ENTER para buscar"></i></label>
                <input class="form-control" type="text" v-model="nameSearch" id="nameSearch" placeholder="Escríbe aquí..." autocomplete="off">
              </div>
              <div class="mb-3 col-12 col-md-6">
                <label class="form-label" for="emailSearch">Correo electrónico <i class="fas fa-question-circle" title="Presione la tecla ENTER para buscar"></i></label>
                <input class="form-control" type="text" v-model="emailSearch" id="emailSearch" placeholder="Escríbe aquí..." autocomplete="off">
              </div>
              <input class="d-none" type="submit">
            </form>

            <div class="row">
              <div class="col-sm-12">
                <!-- && can(canView) -->
                <div v-if="listUsers.total > 0" class="col-12">
                  <div class="table-responsive">
                    <table class="table table-sm table-hover">
                      <thead class="thead-primary">
                        <tr>
                          <th class="role-size">Rol</th>
                          <th class="status">Estado</th>
                          <th>Nombres / Razón Social</th>
                          <th>Correo Electrónico</th>
                          <th>Creado en</th>
                        </tr>
                      </thead>
                      <tbody class="vertical-align-middle">
                        <tr v-for="(user,index) in listUsers.data" :key="index">
                          <td class="role-size">
                            <button class="btn btn-sm btn-primary text-uppercase" style="inline-size: 120px; text-align: center" @click="openModalNewRole(user)">
                              {{ user.roles && user.roles.map(item => item.name).join(', ') || 'Sin rol(s)' }}
                            </button>
                          </td>
                          <td class="status">
                            <span v-if="user.is_active" class="badge rounded-pill bg-success registration-status">Activo</span>
                            <span v-else class="badge rounded-pill bg-danger registration-status">Desactivado</span>
                          </td>
                          <td>{{ user.name }}</td>
                          <td>{{ user.email }}</td>
                          <td>{{ user.created_at | parseDate}}</td>
                        </tr>
                      </tbody>
                    </table>
                    <pagination class="select-none"
                      :limit="5"
                      size="small"
                      :data="listUsers"
                      @pagination-change-page="getListUsers"
                    >
                      <span slot="prev-nav"><i class="fas fa-arrow-left"></i></span>
                      <span slot="next-nav"><i class="fas fa-arrow-right"></i></span>
                    </pagination>
                  </div>
                </div>
                <div v-else class="col-12 mt-3 wihtout-result">
                  <EmptyComponent></EmptyComponent>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="modal-set-new-role" tabindex="-1" aria-labelledby="myLargeModalLabel" aria-modal="true" role="dialog">
      <div class="modal-dialog modal-sm">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title select-none" id="myLargeModalLabel"> Cambiar de rol </h5>
            <button type="button" class="btn-close" @click="closeModalSetRole"></button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-12">
                <label class="form-label" for="list-roles">Nombres<span>*</span></label>
                <select class="form-select text-capitalize select-multiple" id="list-roles" autofocus v-model="idRoles" :size="listRoles.length" multiple>
                  <option v-for="(role, index) in listRoles" :key="index" :value="role.id">{{ role.name }}</option>
                </select>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button class="btn btn-danger" @click="closeModalSetRole">Cancelar</button>
            <button class="btn btn-primary" @click="setNewRole">Guardar</button>
          </div>
        </div>
      </div>
    </div>

  </div>
</template>

<style scoped>
  .role-size {
    inline-size: 130px;
    min-inline-size: 130px;
    text-align: center;
  }
  .select-multiple {
    overflow: hidden;
  }
</style>

<script src="./AssignPermissionComponent.js"></script>
