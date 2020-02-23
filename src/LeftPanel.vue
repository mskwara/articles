<template>
<div id="lefter">

    <div class="leftPanel shadow-sm">
      <h4 class="header">Kategorie</h4>
      <ul class="nav flex-column">
        <li class="nav-item">
          <a class="nav-link" @click="setCategory('')">Wszystkie</a>
        </li>
        <li class="nav-item" :key="cat.key" v-for="cat in categories">
          <a class="nav-link"  @click="setCategory(cat.key)">{{cat.label}}</a>
        </li>
      </ul>

      <h4 class="header">Style</h4>
      <div class="checkboxes">
        <div class="custom-control custom-checkbox">
          <input type="checkbox" class="custom-control-input" id="loose" checked="true" v-model="filter.loose" @click="changeStyle('loose')">
          <label class="custom-control-label cb" for="loose">Luźny</label>
        </div>
        <div class="custom-control custom-checkbox">
          <input type="checkbox" class="custom-control-input" id="serious" checked="true" v-model="filter.serious" @click="changeStyle('serious')">
          <label class="custom-control-label cb" for="serious">Poważny</label>
        </div>
        <div class="custom-control custom-checkbox">
          <input type="checkbox" class="custom-control-input" id="funny" checked="true" v-model="filter.funny" @click="changeStyle('funny')">
          <label class="custom-control-label cb" for="funny">Śmieszny</label>
        </div>
      </div>
      <div class="reset">
        <a @click="resetFilters()">Resetuj filtry</a>
      </div>
    </div>
  </div>
</template>

<script>
import categories from "./categories.js";
import { EventBus } from './event-bus.js';

export default {
  name: 'navbar',
  components: {

  },
  data(){
    return {
      categories: categories.categories,
      filter: {
        category: "",
        loose: true,
        serious: true,
        funny: true,
      }
    }
  },
  methods: {
    goToArticles(){
      this.$router.push({name: 'articles'});
    },
    setCategory(key){
      //this.$router.push({ name: 'articlesfiltered', params: { category: key }});
      this.filter.category = key;
      EventBus.$emit('filter', this.filter);
    },
    changeStyle(cb){
      //this.$router.push({ name: 'articlesfiltered', params: { category: key }});
      if(cb == "loose") {
        this.filter.loose = !this.filter.loose;
      }
      if(cb == "serious") {
        this.filter.serious = !this.filter.serious;
      }
      if(cb == "funny") {
        this.filter.funny = !this.filter.funny;
      }

      EventBus.$emit('filter', this.filter);
    },
    resetFilters(){
      this.filter.category = "";
      this.filter.loose = true;
      this.filter.serious = true;
      this.filter.funny = true;
      EventBus.$emit('filter', this.filter);
    }
  },
  mounted(){
    EventBus.$on('give-me-filters', () => {
      EventBus.$emit('filter', this.filter);
    });
    EventBus.$on('clear-filters', () => {
      this.filter.category = "";
      this.filter.loose = true;
      this.filter.serious = true;
      this.filter.funny = true;
    });
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
}
.leftPanel {
  height: 100%; /* Full-height: remove this if you want "auto" height */
  width: 250px; /* Set the width of the sidebar */
  position: fixed; /* Fixed Sidebar (stay in place on scroll) */
  z-index: 1; /* Stay on top */
  top: 0; /* Stay at the top */
  left: 0;
  background-color: #fafafa;
  overflow-x: hidden; /* Disable horizontal scroll */
  padding-top: 60px;
  border-right: 1px solid gray;
}
.header {
  padding-top: 10px;
  text-align: center;
}
a {
  cursor: pointer;
  padding-bottom: 0 !important;
  padding-top: 0.4rem !important;
}
.custom-checkbox {
  margin-left: 20px;
  display: flex;
  flex-direction: row;
  align-items: center;
  margin-top: 5px;
}
.cb {
  padding-top: 3px;
}
.reset {
  font-size: 9pt;
  text-align: center !important;
  padding-bottom: 20px;
}
.checkboxes {
  margin-bottom: 20px;
}
</style>
