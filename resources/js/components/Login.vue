<template>
  <v-card class="auth-form">
    <loading-overlay v-if="loading"></loading-overlay>
    <v-card-text>
      <img src="/images/logo.png" alt="Logo" class="card-logo" />
      <h2>Login</h2>
      <v-form ref="loginForm">
        <v-text-field v-model="email" label="Email" :rules="emailRules" required></v-text-field>
        <v-text-field
          v-model="password"
          :rules="passwordRules"
          :error-messages="passwordErrors"
          label="Passwort"
          type="password"
          @input="handlePasswordChange"
          required
        ></v-text-field>
        <v-btn block color="primary" @click="login">Login</v-btn>
      </v-form>
    </v-card-text>
    <v-card-actions>
      <v-spacer></v-spacer>
      <forgot-password-link></forgot-password-link>
      <v-btn small text to="/register">No Account?</v-btn>
    </v-card-actions>
  </v-card>
</template>

<script>
import auth from "../auth";
import router from "../router";
import ForgotPasswordLink from "./ForgotPasswordLink";
import LoadingOverlay from "./LoadingOverlay";

export default {
  components: { ForgotPasswordLink },
  data: function() {
    return {
      email: "",
      passwordErrors: [],
      password: "",
      passwordRules: [v => !!v || "Password is required"],
      emailRules: [
        v => !!v || "E-mail is required",
        v => /.+@.+\..+/.test(v) || "Please enter valid E-mail"
      ],
      loading: false
    };
  },
  methods: {
    login() {
      const valid = this.$refs.loginForm.validate();
      this.loading = true;

      if (valid) {
        auth
          .login(this.email, this.password)
          .then(response => {
            router.push("overview");
            this.loading = false;
          })
          .catch(err => {
            this.passwordErrors.push("Password or E-mail not correct");
            this.loading = false;
          });
      }
    },
    handlePasswordChange() {
      this.passwordErrors = [];
    }
  }
};
</script>

<style>
.auth-form {
  margin-bottom: 15px;
  max-width: 450px;
  margin: 0 auto;
}
.card-logo {
  width: 100px;
  margin: 20px auto;
  display: block;
}
</style>