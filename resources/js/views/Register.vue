<template>
  <v-container class="grey lighten-5">
    <v-row no-gutters>
      <v-col cols="12">
        <register-form></register-form>
      </v-col>
    </v-row>
  </v-container>
</template>
<script>
import RegisterForm from "../components/Register";

export default {
  components: {
    RegisterForm
  }
};
</script>
<style>
</style>