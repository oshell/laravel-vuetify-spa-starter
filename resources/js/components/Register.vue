<template>
  <v-card class="auth-form">
    <v-card-text>
      <img src="/images/logo.png" alt="Logo" class="card-logo" />
      <h2>Registration</h2>
      <v-form ref="registerForm">
        <loading-overlay v-if="loading"></loading-overlay>
        <v-text-field v-model="name" :rules="nameRules" label="Name" required></v-text-field>
        <v-text-field
          v-model="email"
          label="Email"
          :rules="emailRules"
          :error-messages="emailErrors"
          required
          @input="handleEmailChange"
        ></v-text-field>
        <v-text-field
          v-model="password"
          label="Passwort"
          :rules="passwordRules"
          type="password"
          required
        ></v-text-field>
        <v-btn block color="primary" @click="register">Register</v-btn>
      </v-form>
    </v-card-text>
    <v-card-actions>
      <v-spacer></v-spacer>
      <v-btn small text to="/">Already have an Account</v-btn>
    </v-card-actions>
  </v-card>
</template>

<script>
import auth from "../auth";
import router from "../router";

export default {
  data: function() {
    return {
      name: "",
      email: "",
      isTeacher: false,
      sex: "",
      birth: "",
      password: "",
      loading: false,
      emailErrors: [],
      nameRules: [v => !!v || "Name is required"],
      passwordRules: [v => !!v || "Password is required"],
      emailRules: [
        v => !!v || "E-mail is required",
        v => /.+@.+\..+/.test(v) || "Please enter valid E-mail"
      ]
    };
  },
  methods: {
    register() {
      const valid = this.$refs.registerForm.validate();
      if (!valid) return;

      this.loading = true;
      const birth = this.birth.split(".").join("-");
      auth
        .register(this.name, this.email, this.password)
        .then(response => {
          router.push("overview");
        })
        .catch(err => {
          this.emailErrors.push("Account with this email already exists.");
          this.loading = false;
        });
    },
    handleEmailChange() {
      this.emailErrors = [];
    }
  }
};
</script>

<style scoped>
.register-form {
  margin-bottom: 15px;
  max-width: 450px;
}

.register-form label {
  padding: 0;
  margin: 0;
}

.v-input--selection-controls {
  margin: 0;
}
</style>