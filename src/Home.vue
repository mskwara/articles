<template>
  <div id="home">
    <navbar></navbar>
    <div class="page">
      <div class="left">
        <left-panel></left-panel>
      </div>
      <div class="right">
        <router-view :key="$route.fullPath"/>
      </div>
    </div>
  </div>
</template>

<script>
import Navbar from './Navbar.vue'
import LeftPanel from './LeftPanel.vue'
import service from "./service.js";

export default {
  name: 'home',
  components: {
    Navbar, LeftPanel
  },
  mounted(){
    if(!service.authenticated) {
        this.$router.replace({ name: "login" });
    }
    else {
      this.$router.push({ name: "articles" });
    }
  },
}
</script>

<style scoped>
body {
  overflow: hidden;
}
#home {
  font-family: 'Avenir', Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
  margin-top: 0;
}
.page {
  display: flex;
  flex-direction: row;
  flex-wrap: nowrap;
  margin-top: 60px;
}
.left {
  width: 300px;
}
.right {
  padding: 50px;
  flex: 1;
}
</style>
