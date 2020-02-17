<template>
  <div id="articlesName">

      <div class="chips">
        <md-chip class="md-accent chip" v-if="filter.category != ''">{{ getLabelForCategory(filter.category) }}</md-chip>
        <md-chip class="md-accent chip" v-else>Wszystkie</md-chip>
        <div :key="style.key" v-for="style in filter.styles">
          <md-chip class="md-primary chip" v-if="style.value == true">{{ getLabelForStyle(style.key) }}</md-chip>
        </div>
      </div>
      <transition name="fade">
        <div v-if="articlesFiltered.length == 0 && !loading && !onlyMyArticles">
          Nikt nie napisał jeszcze żadnego artykułu o wybranych kryteriach.
        </div>
        <div v-if="articlesFiltered.length == 0 && !loading && onlyMyArticles">
          Nie napisałeś jeszcze żadnego artykułu o wybranych kryteriach.
        </div>
      </transition>
      <div class="spinner-border text-primary" role="status" v-if="loading">
        <span class="sr-only">Loading...</span>
      </div>
      <div v-if="articles.length > 0">
        <div class="article" :key="article.id" v-for="article in articlesFiltered">
          <article-short :article="article"></article-short>
        </div>
      </div>
      
      

  </div>
</template>

<script>
import ArticleShort from './ArticleShort.vue';
import categories from "./categories.js";
import service from "./service.js";
import writing_styles from "./writing_styles.js";
import { EventBus } from './event-bus.js';

export default {
  name: 'articlesName',
  components: {
    ArticleShort
  },
  data(){
    return {
      articles: [],
      articlesFiltered: [],
      getArticlesInterval: null,
      loading: true,
      filter: {
        category: "",
        styles: [
          {
            value: true,
            key: "loose"
          },
          {
            value: true,
            key: "serious"
          },
          {
            value: true,
            key: "funny"
          },
        ]
      },
      onlyMyArticles: false,
      onlyMyArticlesImage: "",
      categories: categories.categories,
      writing_styles: writing_styles.writing_styles,
    }
  },
  methods: {
    getArticles(){
      this.$http.get('articles').then(response => {
        if(response.body != null){
          this.articles = response.body;
          this.filterArray();
          if(this.onlyMyArticles == true){
            this.articlesFiltered = this.articlesFiltered.filter(function(article) {
              return article.userId == service.id;
            });
            this.onlyMyArticlesImage = service.avatar;
          }
        }
        else this.articles = [];
        this.loading = false;
      });
    },
    filterArray(){
      var s0 = "";
      var s1 = "";
      var s2 = "";
      if(this.filter.styles[0].value == true) s0 = this.filter.styles[0].key;
      if(this.filter.styles[1].value == true) s1 = this.filter.styles[1].key;
      if(this.filter.styles[2].value == true) s2 = this.filter.styles[2].key;
      var cat = this.filter.category;
      if(cat != ""){
        this.articlesFiltered = this.articles.filter(function(article) {
          return article.tag == cat && (article.style == s0 || article.style == s1 || article.style == s2);
        });
      }
      else {
        this.articlesFiltered = this.articles.filter(function(article) {
          return article.style == s0 || article.style == s1 || article.style == s2;
        });
      }
    },
    getLabelForCategory(key){
      for(var i = 0 ; i < this.categories.length ; i++){
        if(this.categories[i].key == key){
          return this.categories[i].label;
        }
      }
    },
    getLabelForStyle(key){
      for(var i = 0 ; i < this.writing_styles.length ; i++){
        if(this.writing_styles[i].key == key){
          return this.writing_styles[i].label;
        }
      }
    },
  },
  mounted(){
    this.onlyMyArticles = this.$route.params.onlyMyArticles;
    this.getArticles();
    this.getArticlesInterval = setInterval(this.getArticles, 10000);

    EventBus.$on('filter', ({category, loose, serious, funny}) => {
      this.filter.category = category;
      this.filter.styles[0].value = loose;
      this.filter.styles[1].value = serious;
      this.filter.styles[2].value = funny;
      this.filterArray();
      if(this.onlyMyArticles){
        this.articlesFiltered = this.articlesFiltered.filter(function(article) {
          return article.userId == service.id;
        });
      }
    });
    EventBus.$on('only-my-articles', () => {
      this.onlyMyArticles = true;
      this.articlesFiltered = this.articlesFiltered.filter(function(article) {
        return article.userId == service.id;
      });
    });
    EventBus.$on('all-articles', () => {
      this.onlyMyArticles = false;
      this.filterArray();
    });

    EventBus.$emit('give-me-filters');
  },
  destroyed(){
    clearInterval(this.getArticlesInterval);
  }
}
</script>

<style scoped>
#articlesName {
  font-family: 'Avenir', Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  animation-name: animationPop;
  animation-duration: 1s;
}
.article {
  margin-bottom: 30px;
  
}
.chips {
  display: flex;
  flex-direction: row;
  margin-bottom: 10px;
}
.chip {
  margin-right: 10px;
}
@keyframes animationPop {
  from {opacity: 0;}
  to {opacity: 1;}
}
.fade-enter-active {
  transition: opacity 1s;
}
.fade-enter, .fade-leave-to /* .fade-leave-active below version 2.1.8 */ {
  opacity: 0;
}
</style>
