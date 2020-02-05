<template>
  <div id="app">
    <navbar></navbar>
    <div class="page">
      <div class="left">
        <left-panel></left-panel>
      </div>
      <div class="right">
        <router-view></router-view>
      </div>
    </div>
  </div>
</template>

<script>
import Navbar from './Navbar.vue'
import LeftPanel from './LeftPanel.vue'

export default {
  name: 'app',
  components: {
    Navbar, LeftPanel
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
