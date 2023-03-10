<template>
  <div>
    <LoaderComponent v-if="isLoader"></LoaderComponent>
    <div class="row">
      <div class="col-sm-12">
        <div class="card">
          <div class="card-body">
            <div class="card-title select-none">
              <h5 class="color-customized">Mi información</h5>
            </div>
            <hr>
            <div class="row">
              <div class="col-12 col-sm-12 col-md-6 col-lg-4 col-xl-3">
                <figure class="img-avatar">
                  <img :src="`${END_POINT}backend/assets/images/stickers/avatar.png`" class="h-100" alt="Imagen de avatar">
                </figure>
                <span :class="['select-none','badge', is_active ? 'bg-success' : 'bg-danger']">
                  Usuario {{ is_active ? 'Activo' : 'Inhabilitado' }}
                </span>
              </div>
              <form @submit.prevent="updateProfile" autocomplete="off" class="col-12 col-sm-12 col-md-6 col-lg-4 col-xl-3">
                <input type="hidden" name="id" v-model="id">
                <div class="form-group mb-3">
                  <label for="nickName" class="select-none mb-0">Nombres</label>
                  <input type="text" name="nickName" v-model="nickName" id="nickName" class="form-control form-line" placeholder="Complete el campo...">
                </div>
                <!-- <div class="form-group mb-3">
                  <label for="name" class="select-none mb-0">Nombres</label>
                  <input type="text" v-model="name" id="name" class="form-control form-line" placeholder="Complete el campo...">
                </div> -->
                <div class="form-group mb-3">
                  <label for="email" class="select-none mb-0">Correo electrónico</label>
                  <input type="text" name="email" v-model="email" id="email" class="form-control form-line" placeholder="Complete el campo...">
                </div>

                <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="hasChangePassword" name="hasChangePassword" v-model="hasChangePassword">
                  <label class="form-check-label select-none cursor-pointer" for="hasChangePassword">
                    Cambiar contraseña
                  </label>
                </div>
                <template v-if="hasChangePassword">
                  <div class="form-group mb-3">
                    <label for="currentPassword" class="select-none mb-0">Contraseña actual</label>
                    <input type="text" name="currentPassword" v-model="currentPassword" id="currentPassword" class="form-control password form-line" placeholder="******">
                  </div>
                  <div class="form-group mb-3">
                    <label for="newPassword" class="select-none mb-0">Nueva Contraseña</label>
                    <input type="text" name="newPassword" v-model="newPassword" id="newPassword" class="form-control password form-line" placeholder="******">
                  </div>
                </template>
                <button type="submit" class="btn btn-primary">Actualizar</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script src="./ProfileComponent.js"></script>
