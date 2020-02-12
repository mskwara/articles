<template>
<div id="lefter">

    <div class="leftPanel shadow-sm">
      <h4 class="header">Kategorie</h4>
      <ul class="nav flex-column">
        <li class="nav-item">
          <a class="nav-link active" @click="goToArticles()">All</a>
        </li>
        <li class="nav-item" :key="cat.key" v-for="cat in categories">
          <a class="nav-link active"  @click="setRoute(cat.key)">{{cat.label}}</a>
        </li>
      </ul>
    </div>
  </div>
</template>

<script>
import categories from "./categories.js";

export default {
  name: 'navbar',
  components: {

  },
  data(){
    return {
      categories: categories.categories,
    }
  },
  methods: {
    goToArticles(){
      this.$router.push({name: 'articles'});
    },
    setRoute(key){
      this.$router.push({ name: 'articlesfiltered', params: { category: key }});
    },
  }
}
</script>

<style scoped>

#lefter {
font-family: 'Avenir', Helvetica, Arial, sans-serif;
-webkit-font-smoothing: antialiased;
-moz-osx-font-smoothing: grayscale;
text-align: left;
color: #2c3e50;
overflow: hidden;
}
.leftPanel {
  min-width: 150px;
  width: 300px;
  height: 100vh;
  border-right: 1px solid gray;
  background-color: #fafafa;

}
.header {
  padding-top: 10px;
  text-align: center;
}
a {
  cursor: pointer;
}
</style>
