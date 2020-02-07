<template>
<div id="lefter">

    <div class="leftPanel shadow-sm">
      <h4 class="header">Categories</h4>
      <ul class="nav flex-column">
        <li class="nav-item">
          <a class="nav-link active" href="#">All</a>
        </li>
        <li class="nav-item" :key="cat.key" v-for="cat in categories">
          <a class="nav-link active" href="#">{{cat.label}}</a>
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
  width: 300px;
  min-width: 300px;
  height: 100vh;
  border-right: 1px solid gray;
  background-color: #fafafa;
  position: fixed;
}
.header {
  padding-top: 10px;
  text-align: center;
}
</style>
