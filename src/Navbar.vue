<template>
<div id="navbar">
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top shadow-sm">
    <router-link class="navbar-brand" to="/"><img class="logo" src="./assets/logo.png" /></router-link>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item">
          <a class="nav-link routerlink" @click="routePrzeglad()">Przegląd</a>
        </li>
        <li class="nav-item">
          <a class="nav-link routerlink" @click="routeOnlyMyArticles()">Moje artykuły</a>
        </li>
        <li class="nav-item">
          <a class="nav-link routerlink" @click="setRoute('statistics')">Statystyki</a>
        </li>
        <li class="nav-item">
          <router-link class="nav-link routerlink" to="/profile">
            <img v-if="image.length > 0" class="avatar" :src="image" />
            <img v-else src="./assets/avatar.png" class="mr-3 avatar">
            {{username}}
          </router-link>
        </li>
        <li class="nav-item" v-if="isAdmin()">
          <button class="btn btn-outline-info" @click="setRoute('adminpanel')">Panel admina</button>
        </li>
        <li class="nav-item">
          <button class="btn btn-outline-success write" @click="setRoute('writing')">Napisz co myślisz</button>
        </li>
      </ul>
      <a class="nav-link logout" @click="logout()">Wyloguj</a>
      <form class="form-inline my-2 my-lg-0" v-on:submit.prevent="search()">
        <input class="form-control mr-sm-2" type="search" placeholder="Wpisz szukaną frazę" aria-label="Search" v-model="searchPhrase">
        <button class="btn btn-outline-warning my-2 my-sm-0" type="submit" :disabled="searchPhrase == ''">Szukaj</button>
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
      onlyMyArticles: false,
    }
  },
  methods: {
    setRoute(link){
      //this.$router.push(link);
      this.$router.push({ name: link});
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
    },
    routeOnlyMyArticles(){
      this.onlyMyArticles = true;
      this.$router.push({ name: 'articles', params: { onlyMyArticles: this.onlyMyArticles }});
      EventBus.$emit('only-my-articles');
    },
    routePrzeglad(){
      this.onlyMyArticles = false;
      this.$router.push({ name: 'articles', params: { onlyMyArticles: this.onlyMyArticles }});
      EventBus.$emit('all-articles');
    },
    isAdmin(){
      if(service.id == 47 && service.nick == "admin"){
        return true;
      }
      return false;
    }
  },
  mounted(){
    this.username = service.name;
    this.image = service.avatar;

    EventBus.$on('update-user', () => {
      this.image = service.avatar;
      this.username = service.name;
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
  min-width: 30px;
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
a {
  cursor: pointer;
}
</style>
