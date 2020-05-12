<template>
  <div :class="`${inline ? 'loading-inline' : 'loading-overlay'}`">
    <v-progress-circular
      :size="50"
      color="primary"
      indeterminate
      justify="center"
    ></v-progress-circular>
  </div>
</template>

<script>
export default {
  props: ['inline']
}
</script>

<style>
.loading-overlay {
    width: 100%;
    z-index: 1;
    height: 100%;
    top: 0;
    left: 0;
    background: rgba(255, 255, 255, 0.7);
    position: absolute;
}

.loading-overlay > div {
    height: 50px;
    width: 100%!important;
    margin-top: 50%;
}

.loading-inline {
    width: 100%;
    padding: 50px 0;
    margin: 0 auto;
    background: rgba(255, 255, 255, 0.7);
}

.loading-inline > div {
    height: 50px;
    width: 100%!important;
}
</style>