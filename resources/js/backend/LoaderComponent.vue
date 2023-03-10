<template>
  <div>
    <div class="loader">
      <img :src="`${END_POINT}/loader.svg`" class="select-none image-loader" alt="Cargando...">
      <h6 class="select-none">Cargando...</h6>
    </div>
  </div>
</template>
<style scoped>
  .loader {
    position: fixed;
    z-index: 1055;
    width: 100vw;
    height: 100vh;
    inset: 0;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    gap: 1rem;
    background-color: #000000f5;
  }
  .image-loader {
    height: 100px ;
  }
</style>
