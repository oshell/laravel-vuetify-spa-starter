<template>
  <v-app-bar color="white" dense collapse-on-scroll short fixed>
    <v-toolbar-title>
      <router-link to="/overview" class="nav-link">
        <img src="/images/logo.png" alt="Logo" class="nav-logo">
      </router-link>
    </v-toolbar-title>

    <v-spacer></v-spacer>

    <v-menu left bottom>
      <template v-slot:activator="{ on }">
        <v-btn icon v-on="on">
          <v-icon>mdi-dots-vertical</v-icon>
        </v-btn>
      </template>

      <v-list>
        <v-list-item @click="logout">
          <v-list-item-title>Logout</v-list-item-title>
        </v-list-item>
      </v-list>
    </v-menu>
  </v-app-bar>
</template>

<script>
import auth from "../auth";
import router from "../router";

export default {
  methods: {
    async logout() {
      this.$emit("loading", true);
      await auth.logout();
      this.$emit("loading", false); 
      router.push({ name: "home" });
    }
  }
};
</script>

<style>
.nav-link {
  color: white !important;
  text-decoration: none;
}
.nav-logo {
  height: 30px;
}
</style>