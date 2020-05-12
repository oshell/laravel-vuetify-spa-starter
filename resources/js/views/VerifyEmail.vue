<template>
  <v-card>
    <v-card-text>
      {{message}}
    </v-card-text>
  </v-card>
</template>

<script>
import api from '../api';
import router from '../router';

export default {
  data() {
    return {
      message: ''
    }
  },
  created() {
    api.email
      .verify(this.$route.params.uid, this.$route.params.hash)
      .then(response => {
        this.message = 'Email verified. You will be redirected in 3 seconds';
        setTimeout(() => {
          router.push({ name: "home" });
        }, 3000);
      })
      .catch(err => {
        this.message = 'Email verification failed';
      });
  }
}
</script>

<style>

</style>