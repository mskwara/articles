<template>
<div id="navbar">
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top shadow-sm">
    <router-link class="navbar-brand" to="/">Articles</router-link>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item">
          <router-link class="nav-link" to="/articles">Home</router-link>
        </li>
        <li class="nav-item">
          <router-link class="nav-link" to="/myarticles">My articles</router-link>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#"><img class="avatar" src="./assets/avatar.jpg" />{{username}}</a>
        </li>
        <li class="nav-item">
          <button class="btn btn-outline-success write" @click="setRoute('/writing')">Write an article</button>
        </li>
      </ul>
      <a class="nav-link logout" @click="logout()">Wyloguj</a>
      <form class="form-inline my-2 my-lg-0">
        <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search" v-model="searchPhrase">
        <button class="btn btn-outline-warning my-2 my-sm-0" type="submit" :disabled="searchPhrase == ''" @click="search">Search</button>
      </form>
    </div>
  </nav>

</div>
</template>

<script>
import service from "./service.js";

export default {
  name: 'navbar',
  data(){
    return {
      username: "",
      searchPhrase: "",
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
      this.$router.replace({ name: "login" });
    },
    search(){
      this.$router.push({ name: 'articlessearched', params: { phrase: this.searchPhrase }});
      this.searchPhrase = "";
    }
  },
  mounted(){
    this.username = service.name;
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
</style>
