<template>
  <v-card>
    <v-card-text v-if="user.name">
      Welcome {{ user.name }}
    </v-card-text>
  </v-card>
</template>

<script>
import api from '../api';

export default {
  data() {
    return {
      user: {}
    }
  },
  created() {
    api.user.get().then(response => {
      this.user = response.data;
    })
  }
}
</script>

<style>

</style>