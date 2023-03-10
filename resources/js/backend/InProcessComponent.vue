<script>
  export default {
    name: 'ProcessInComponent'
  }
</script>

<template>
  <div class="sendding">
    <i class="fas fa-spinner fa-2x d-block text-white spinner"></i>
    <span class="text-white">Enviando</span>
  </div>
</template>

<style>
  .sendding {
    background-color: hsla(0, 0%, 0%, 0.808);
    position: fixed;
    z-index: 1050;
    top: 0;
    left: 0;
    inset: 1;
    inline-size: 100vw;
    block-size: 100vh;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    /* InProccessComponent */
    gap: .5em;
    user-select: none;
  }

  .spinner {
    animation: animation-spinner 1s linear infinite backwards;
  }

  @keyframes animation-spinner {
    to {
      transform: rotate(360deg);
    }
  }
</style>
