<template>
  <div>
    <LoaderComponent v-if="isLoading"></LoaderComponent>
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <div class="card-title select-none">
              <h5 class="color-customized">Nueva cotización</h5>
            </div>
            <div class="row">
              <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-3">
                <label for="idEnterprise">Empresa </label>
                <div class="input-group">
                  <select v-model="idEnterprise" id="idEnterprise" class="form-control" readonly>
                    <option v-for="(enterprise, index) in enterprises" :key="index" :value="enterprise.id">
                      {{enterprise.fullName}}
                    </option>
                  </select>
                  <span class="input-group-btn input-group-append">
                    <button class="btn btn-primary bootstrap-touchspin-up" title="Seleccionar" @click="showOptionsSelectEnterprise" type="button">
                      <i class="fas fa-redo-alt"></i>
                    </button>
                  </span>
                </div>
              </div>
              <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-3">
                <label for="typeContact">Contacto con</label>
                <select v-model="typeContact" id="typeContact" class="form-select">
                  <option :value="0">Documento</option>
                  <option :value="1">Verbal</option>
                </select>
              </div>
              <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-3">
                <label for="type_contact">Adjuntar documento</label>
                <input type="file" class="form-control" id="fileContact" @change="fileUploadHandleChange">
              </div>
            </div>
            <div class="row">
              <div class="col-sm-12 col-md-6">

              </div>
              <div class="col-sm-12 col-md-6">

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script src="./CreateComponent.js"></script>
