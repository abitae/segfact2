<script src="./UserComponent.js"></script>

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
                <button v-show="can(canCreate)" class="btn btn-primary" @click="openModalCreateUser">
                  <i class="fas fa-plus"></i> Nuevo
                </button>
              </div>
              <form @submit.prevent="getListDataUsers()" class="row">
                <div class="mb-3 col-12 col-md-6 col-lg-3 col-xl-2">
                  <label class="form-label" for="nameSearch">Nombres <i class="fas fa-question-circle" title="Presione la tecla ENTER para buscar"></i></label>
                  <input class="form-control" type="text" v-model="nameSearch" id="nameSearch" placeholder="Escríbe aquí..." autocomplete="off">
                </div>
                <div class="mb-3 col-12 col-md-6 col-lg-3 col-xl-2">
                  <label class="form-label" for="emailSearch">Correo electrónico <i class="fas fa-question-circle" title="Presione la tecla ENTER para buscar"></i></label>
                  <input class="form-control" type="text" v-model="emailSearch" id="emailSearch" placeholder="Escríbe aquí..." autocomplete="off">
                </div>
                <input class="d-none" type="submit">
              </form>

              <div v-if="listDataUsers.total > 0 && can(canView)" class="col-12">
                <div class="table-responsive">
                  <table class="table table-sm table-striped table-hover">
                    <thead class="thead-primary">
                      <tr>
                        <th class="options" v-show="can(canEdit,canUpdateStatus)">Opciones</th>
                        <th class="status">Estado</th>
                        <th>Nombres / Razón Social</th>
                        <th>Apodo</th>
                        <th>Correo Electrónico</th>
                        <th class="text-center column-cuota">Cuota</th>
                        <th class="text-center">Es vendedor</th>
                        <th class="text-center column-date">Creado en</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(user,index) in listDataUsers.data" :key="index">
                        <td class="options" v-show="can(canEdit,canUpdateStatus)">
                          <button
                            v-show="can(canEdit)"
                            class="btn btn-sm btn-primary"
                            @click="openModalEditUser(user)"
                          > <i class="far fa-edit"></i>
                          </button>
                          <button
                          v-show="can(canUpdateStatus)"
                          @click="updataStatus(user)"
                          :class="`btn btn-sm btn-${ user.is_active ? 'success' : 'danger' }`">
                            <i v-if="user.is_active" class="far fa-check-circle"></i>
                            <i v-else class="far fa-times-circle"></i>
                          </button>
                        </td>
                        <td class="status">
                          <span v-if="user.is_active" class="badge rounded-pill bg-success registration-status">Activo</span>
                          <span v-else class="badge rounded-pill bg-danger registration-status">Desactivado</span>
                        </td>
                        <td>{{ user.name }}</td>
                        <td>{{ user.nickName }}</td>
                        <td>{{ user.email }}</td>
                        <td class="column-cuota"> S/. {{ user.cuota }}</td>
                        <td class="column-cuota text-center"> {{ user.is_seller ? 'Vendedor' : '-' }}</td>
                        <td class="text-center">{{ user.created_at | parseDate }}</td>
                      </tr>
                    </tbody>
                  </table>
                  <pagination class="select-none"
                    :limit="5"
                    size="small"
                    :data="listDataUsers"
                    @pagination-change-page="getListDataUsers"
                  >
                    <span slot="prev-nav"><i class="fas fa-arrow-left"></i></span>
                    <span slot="next-nav"><i class="fas fa-arrow-right"></i></span>
                  </pagination>
                </div>
              </div>

              <div v-else class="col-12 mt-3 wihtout-result">
                <img :src="`${END_POINT}/backend/assets/images/stickers/box-empty.png`" alt="Imagen de lista vacía">
                <h6 class="wihtout-result-title">Sin resultados</h6>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="modal-user" tabindex="-1" aria-labelledby="myLargeModalLabel" aria-modal="true" role="dialog">
      <div class="modal-dialog modal-sm">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title select-none" id="myLargeModalLabel"> {{ modalTitle }} Usuario </h5>
            <button type="button" class="btn-close" @click="closeModal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="mb-3 col-12">
                <label class="form-label" for="name">Nombres</label>
                <input v-model="name" class="form-control" type="text" id="name" placeholder="Complete..." autocomplete="off">
              </div>
              <div class="mb-3 col-12">
                <label class="form-label" for="nickName">Apodo</label>
                <input v-model="nickName" class="form-control" type="text" id="nickName" placeholder="Complete..." autocomplete="off">
              </div>
              <div class="mb-3 col-12">
                <label class="form-label" for="email">Correo Electrónico</label>
                <input v-model="email" class="form-control" type="email" id="email" placeholder="ejemplo@gmail.com" autocomplete="off">
              </div>
              <div class="mb-3 col-12">
                <label class="form-label" for="cuota">Cuota<i class="text-muted"> - Opcional</i></label>
                <input v-model="cuota" class="form-control" type="number" id="cuota" placeholder="EJEM: 100000.00" autocomplete="off">
              </div>
              <div class="mb-3 col-12">
                <input type="checkbox" id="is_seller" v-model="isSeller" class="mr-2">
                <label for="is_seller" class="cursor-pointer user-select-none">Mostrar como vendedor</label>
              </div>

              <div class="col-sm-12" v-if="id != 0">
                <input type="checkbox" id="update-password" v-model="isUpdatePassword" class="mr-2">
                <label for="update-password" class="cursor-pointer user-select-none">Cambiar contraseña</label>
                <template v-if="isUpdatePassword">
                  <div class="mb-3 col-12">
                    <label class="form-label" for="password">Contraseña<span>*</span></label>
                    <input v-model="password" class="form-control password" type="text" id="password" name="new-passsord" placeholder="*******" autocomplete="off">
                  </div>
                  <div class="mb-3 col-12">
                    <label class="form-label" for="repeat-password">Repita contraseña<span>*</span></label>
                    <input v-model="repeatPassword" class="form-control password" type="text" id="repeat-password" placeholder="*******" autocomplete="off">
                  </div>
                </template>
              </div>
              <template v-else>
                <div class="text-center">
                  <span>La contraseña por defecto es</span>
                  <br>
                  <span class="text-primary cursor-pointer" @click="generateClipboard(generatePassword)"><b>{{generatePassword}}</b></span>
                </div>
              </template>
            </div>
          </div>
          <div class="modal-footer">
            <button class="btn btn-danger" data-dismiss="modal" @click="closeModal">Cancelar</button>
            <button class="btn btn-primary" @click="createOrUpdate">Guardar</button>
          </div>
        </div>
      </div>
    </div>

  </div>
</template>

<style>
  .column-date {
    inline-size: 150px;
    min-inline-size: 150px;
    max-inline-size: 150px;
  }

  .column-cuota {
    inline-size: 100px;
    min-inline-size: 100px;
    max-inline-size: 100px;
  }
</style>

