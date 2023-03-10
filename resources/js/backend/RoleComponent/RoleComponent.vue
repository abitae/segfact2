<template>
  <div>
    <div class="row">
      <div class="col-sm-12 col-md-6 col-lg-5">
        <div class="card">
          <div class="card-body">
            <div class="card-title select-none title-with-button">
              <h5 class="color-customized">Lista de roles</h5>
              <button class="btn btn-primary" @click="openModalRole">
                <i class="fas fa-plus"></i>
                <span class="d-none d-md-inline-block">Nuevo</span>
              </button>
            </div>
            <div class="row">
              <div class="col-sm-12">
                <div class="table-responsive">
                  <table class="table table-sm table-striped">
                    <thead>
                      <tr>
                        <th class="text-center">N°</th>
                        <th class="column-permission" v-if="can(canEdit)">Option</th>
                        <th class="column-role"> <span v-if="can(canEdit)">Permissions - </span> Roles</th>
                        <th class="column-date text-center d-none d-md-table-cell">Creado en</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(role, index) in listRoles" :key="index">
                        <td class="text-center">
                          {{ String(index + 1).padStart(2, '0') }}
                        </td>
                        <td class="text-capitalize column-permission" v-if="can(canEdit)">
                          <button class="btn btn-sm btn-primary" @click="asignPermission(role)">
                            <i class="fas fa-tasks"></i> Asignar
                          </button>
                        </td>
                        <td class="column-role text-capitalize"> <i class="fas fa-user-tag"></i> <span v-if="can(canEdit)">{{ role.permissions_count }} - </span>{{ role.name }}  </td>
                        <td class="column-date text-center d-none d-md-table-cell">{{ role.created_at | parseDate}} </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-sm-12 col-md-6 col-lg-7" v-if="idRole">
        <div class="card">
          <div class="card-body">
            <div class="card-title title-with-button align-items-center">
              <h5 class="color-customized">
                <span class="text-uppercase">{{ currentRol.name }}</span> asignación de permisos
              </h5>
              <a href="javascript:;" class="btn btn-danger" @click="resetAsignPermissions()">
                <i class="fas fa-angle-left"></i>
                <span class="d-none d-md-inline-block">Back</span>
              </a>
            </div>
            <div class="table-responsive">
              <table class="table table-sm table-hover table-bordered table-striped">
                <thead>
                  <tr>
                    <th>Name</th>
                    <th class="column-checked">
                      <!-- <label for="check-all"> -->
                        <input type="checkbox" id="check-all" v-model="check_all" value="check_all">
                      <!-- </label> -->
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(permission, index) in listPermissions" :key="index">
                    <td class="text-capitalize">{{ permission.name }}</td>
                    <td class="column-checked user-select-none">
                      <input type="checkbox"
                        v-model="selectedPermits"
                        :value="permission.id"
                        :id="`id-rol-${permission.id}`"
                        class="cursor-pointer form-check-input">
                      <label :for="`id-rol-${permission.id}`" class="cursor-pointer mb-0">
                        Select
                      </label>
                    </td>
                  </tr>
                </tbody>
                <tfoot>
                  <tr>
                    <th>Name</th>
                    <th class="column-checked">
                      <input type="checkbox" id="check-all" v-model="check_all" value="check_all">
                    </th>
                  </tr>
                </tfoot>
              </table>
            </div>
            <div class="col-12">
              <button class="btn btn-primary" @click="saveRolWithPermissions">Save</button>
            </div>
          </div>
        </div>
      </div>

      <!-- <div class="col-sm-12 col-md-9">
        <div class="card">
          <div class="card-body">
            <div class="card-title select-none title-with-button">
              <h5 class="color-customized">Lista de permisos</h5>
            </div>
            <div class="row">
              <div class="col-12">
                <template v-for="(permission, index) in listPermissions">
                  <span class="span" :key="index"> {{ permission.name }}</span>
                </template>
              </div>
            </div>
          </div>
        </div>
      </div> -->
    </div>

    <div class="modal fade" id="modal-role" tabindex="-1" aria-labelledby="myLargeModalLabel" aria-modal="true" role="dialog">
      <div class="modal-dialog modal-sm">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title select-none" id="myLargeModalLabel"> Nuevo rol </h5>
            <button type="button" class="btn-close" @click="closeModal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="mb-3 col-12">
                <label class="form-label" for="name">Nombre</label>
                <input v-model="name" class="form-control" type="text" id="name" placeholder="Complete..." autocomplete="off">
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button class="btn btn-danger" data-dismiss="modal" @click="closeModal">Cancelar</button>
            <button class="btn btn-primary" @click="sendForm">Guardar</button>
          </div>
        </div>
      </div>
    </div>

  </div>
</template>

<style scoped>
  .span {
    background: var(--bs-primary);
    color: white;
    padding: 0.1em 0.4em;
    border-radius: 2em;
    display: inline-block;
    margin: .1em;
  }

  .column-permission {
    inline-size: 90px;
    min-inline-size: 90px;
    text-align: center;
  }

  .column-role {
    min-inline-size: 150px;
  }

  .column-checked {
    min-inline-size: 70px;
    inline-size: 70px;
    text-align: center;
  }
</style>

<script type="text/javascript" src="./RoleComponent.js"></script>
