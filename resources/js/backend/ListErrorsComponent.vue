<template>
  <div class="select-none">
    <h6 class="toast-list-error-title">Errores encontrados</h6>
    <ul style="padding-left: 1rem">
      <li v-for="(error,index) in listErrors" :key="index">
        {{ error }}
      </li>
    </ul>
    <small class="toast-list-error-title">Corrija los errores para continuar</small>
  </div>
</template>

<script>
export default {
  props:['listErrors']
}
</script>
