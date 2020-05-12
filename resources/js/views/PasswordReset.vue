<template>
  <v-card>
    <v-card-text>
      <v-form  v-if="!message">
        Gib ein neues Passwort an.
        <v-text-field type="password" v-model="password"></v-text-field>
        <v-btn @click="reset">Bestätigen</v-btn>
      </v-form>
      <v-alert v-if="message" :type="messageType">
        {{message}}
      </v-alert>
    </v-card-text>
  </v-card>
</template>

<script>
import api from '../api';
import router from '../router';

export default {
  data() {
    return {
      password: '',
      message: '',
      messageType: ''
    }
  },
  methods: {
    reset() {
      api.password
        .reset(this.$route.params.uid, this.$route.params.hash, this.password)
        .then(response => {
          this.messageType = "success";
          this.message = 'Passwort erfolgreich angepasst. Du wirst in 3 Sekunden weitergeleitet.';
          setTimeout(() => {
            router.push({ name: "home" });
          }, 3000);
        })
        .catch(err => {
          this.messageType = "error";
          this.message = 'Der Code zum Zurücksetzen ist abgelaufen. Leite die "Passwort vergessen" Funktion bitte neu ein.';
        });
    }
  }
}
</script>

<style>

</style>