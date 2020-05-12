<template>
  <v-card>
    <v-card-text v-if="user.name">
      Welcome {{ user.name }}
    </v-card-text>
  </v-card>
</template>

<script>
import auth from '../auth';

export default {
  data() {
    return {
      user: {}
    }
  },
  created() {
    this.user = auth.user()
  }
}
</script>

<style>

</style>