<template>
  <div>
    <v-app>
      <loading-overlay v-if="loading" :inline="true"></loading-overlay>
      <app-bar v-if="showAppbar" @loading="handleLoading"></app-bar>
      <v-container v-if="!loading" class="main-content grey lighten-5">
        <router-view></router-view>
      </v-container>
    </v-app>
  </div>
</template>
<script>
import auth from "../auth";

export default {
  data() {
    return {
      loading: false
    };
  },
  computed: {
    showAppbar() {
      return this.$route.meta.requiresAuth;
    }
  },
  methods: {
    handleLoading(state) {
      this.loading = state;
    }
  }
};
</script>
<style>
#app {
  background-color: #fafafa;
}

.main-content {
  padding-top: 80px;
}
</style>