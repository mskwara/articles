<template>
  <div id="app">
    <router-view @authenticated="setAuthenticated" />
  </div>
</template>

<script>
import service from "./service.js";

export default {
  name: 'app',
  components: {
  },
  data(){
    return {
      authenticated: false,
    }
  },
  mounted(){
    if(!service.authenticated) {
        this.$router.replace({ name: "login" });
    }
    else {
      this.$router.replace({ name: "home" });
    }
  },
  methods: {
      setAuthenticated(status) {
          service.authenticated = status;
      },
      logout() {
          this.authenticated = false;
      },

  }
}
</script>

<style scoped>
body {
  overflow: hidden;
}
#app {
  font-family: 'Avenir', Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
  margin-top: 0;
}

</style>
