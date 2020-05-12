<template>
  <v-container class="grey lighten-5">
    <v-row no-gutters>
      <v-col cols="12">
        <login></login>
      </v-col>
    </v-row>
  </v-container>
</template>
<script>
import Login from "../components/Login";

export default {
  components: {
    Login
  }
};
</script>
<style>
</style>