<template>
  <div>
    <v-btn small text @click="dialog = true">Forgot your password?</v-btn>
    <v-dialog v-model="dialog" max-width="320">
      <v-card>
        <loading-overlay v-if="loading"></loading-overlay>
        <v-card-title class="headline">Forgot your password?</v-card-title>

        <v-card-text>
          Please enter your E-mail and we will send you instructions with the next step.
          <br />

          <v-text-field
            label="Email-Adresse"
            v-model="email"
            :error-messages="errors"
            @input="handleChange"
          ></v-text-field>
        </v-card-text>

        <v-card-actions>
          <v-spacer></v-spacer>

          <v-btn color="green darken-1" text @click="dialog = false">Cancel</v-btn>

          <v-btn
            dark
            color="green darken-1"
            @click="initPasswordReset"
            :disabled="loading"
            :loading="loading"
          >Accept</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
import api from "../api";
import LoadingOverlay from "./LoadingOverlay";

export default {
  components: {
    LoadingOverlay
  },
  data() {
    return {
      email: "",
      dialog: false,
      errors: [],
      loading: false
    };
  },
  methods: {
    initPasswordReset() {
      this.loading = true;

      api.password
        .initReset(this.email)
        .then(response => {
          this.dialog = false;
          this.loading = false;
        })
        .catch(err => {
          this.errors.push("E-mail does not exist");
          this.loading = false;
        });
    },
    handleChange() {
      this.errors = [];
    }
  }
};
</script>

<style>
</style>