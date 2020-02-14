<template>
<div id="navbar">
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top shadow-sm">
    <router-link class="navbar-brand" to="/"><img class="logo" src="./assets/logo.png" /></router-link>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item">
          <router-link class="nav-link routerlink" to="/articles">Przegląd</router-link>
        </li>
        <li class="nav-item">
          <router-link class="nav-link routerlink" to="/myarticles">Moje artykuły</router-link>
        </li>
        <li class="nav-item">
          <router-link class="nav-link routerlink" to="/profile">
            <img v-if="image.length > 0" class="avatar" :src="image" />
            <img v-else src="./assets/avatar.png" class="mr-3 avatar">
            {{username}}
          </router-link>
        </li>
        <li class="nav-item">
          <button class="btn btn-outline-success write" @click="setRoute('/writing')">Napisz artykuł</button>
        </li>
      </ul>
      <a class="nav-link logout" @click="logout()">Wyloguj</a>
      <form class="form-inline my-2 my-lg-0">
        <input class="form-control mr-sm-2" type="search" placeholder="Wpisz szukaną frazę" aria-label="Search" v-model="searchPhrase">
        <button class="btn btn-outline-warning my-2 my-sm-0" type="submit" :disabled="searchPhrase == ''" @click="search">Szukaj</button>
      </form>
    </div>
  </nav>

</div>
</template>

<script>
import service from "./service.js";
import { EventBus } from './event-bus.js';

export default {
  name: 'navbar',
  data(){
    return {
      username: "",
      searchPhrase: "",
      image: "",
    }
  },
  methods: {
    setRoute(link){
      this.$router.push(link);
    },
    logout(){
      service.authenticated = false;
      service.id = null;
      service.nick = "";
      service.name = "";
      service.surname = "";
      service.email = "";
      service.description = "";
      service.avatar = "";
      EventBus.$emit('logout');
      this.$router.replace({ name: "login" });
    },
    search(){
      this.$router.push({ name: 'articlessearched', params: { phrase: this.searchPhrase }});
      this.searchPhrase = "";
    }
  },
  mounted(){
    this.username = service.name;
    this.image = service.avatar;

    EventBus.$on('update-user', () => {
      this.image = service.avatar;

    });
  }
}
</script>

<style scoped>
#navbar {
  font-family: 'Avenir', Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
  overflow-y: hidden;
}
.navbar {
  max-height: 65px !important;
}
.avatar {
  width: 30px;
  border-radius: 50px;
  margin-right: 10px;
}
.nav-item {
  display: flex !important;
  align-items: center;
}
.nav-link {
  display: flex !important;
  align-items: center;
  outline: 0;
}
.routerlink:hover {
  color: white !important;
}
.header {
  padding-top: 10px;
}
.write {
  margin-left: 100px;
  width: 200px;
}
.logout {
  cursor: pointer;
}
.logo {
  width: 130px;
  outline: 0;
}
.navbar-brand {
  outline: 0;
}
</style>
