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
import { EventBus } from './event-bus.js';

export default {
  name: 'home',
  components: {
    Navbar, LeftPanel
  },
  data(){
    return {
      checkingIfUserExists: null,
    }
  },
  methods: {
    checkIfUserExists(){
      var user = {
        id: service.id
      }
      this.$http.post('validateIfUserExists', user).then(response => {
        if(response.body == "false"){
          service.authenticated = false;    //logout
          service.id = null;
          service.nick = "";
          service.name = "";
          service.surname = "";
          service.email = "";
          service.description = "";
          service.avatar = "";
          clearInterval(this.checkingIfUserExists);
          this.$router.replace({ name: "login" });
        }
      });
    }
  },
  mounted(){
    if(!service.authenticated) {
        this.$router.replace({ name: "login" });
    }
    else {
      this.$router.push({ name: "articles" });
      this.checkingIfUserExists = setInterval(this.checkIfUserExists, 10000);
    }
    EventBus.$on('logout', () => {
      clearInterval(this.checkingIfUserExists);
    });
  },
  destroyed(){
    clearInterval(this.checkingIfUserExists);
  }
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
  width: 250px;
}
.right {
  padding: 50px;
  padding-top: 20px;
  flex: 1;
}
</style>
