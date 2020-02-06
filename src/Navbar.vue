<template>
<div id="navbar">
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top shadow-sm">
    <router-link class="navbar-brand" to="/">Articles</router-link>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item active">
          <router-link class="nav-link" to="/">Home <span class="sr-only">(current)</span></router-link>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">My articles</a>
        </li>
        <li class="nav-item">

          <a class="nav-link" href="#"><img class="avatar" src="./assets/avatar.jpg" />Michał</a>
        </li>
      </ul>
      <form class="form-inline my-2 my-lg-0">
        <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
          <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
        </form>
      </div>
    </nav>

  </div>
</template>

<script>

export default {
  name: 'navbar',
  components: {

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
.leftPanel {
  width: 300px;
  height: 100vh;
  border-right: 1px solid gray;
  background-color: #fafafa;
}
.header {
  padding-top: 10px;
}
</style>
