<template>
<div v-infinite-scroll="appendToArticles" infinite-scroll-disabled="busy" infinite-scroll-distance="10">
  <div id="articlesName">
      <div class="chips">
        <md-chip class="md-accent chip" v-if="filter.category != ''">{{ getLabelForCategory(filter.category) }}</md-chip>
        <md-chip class="md-accent chip" v-else>Wszystkie</md-chip>
        <div :key="style.key" v-for="style in filter.styles">
          <md-chip class="md-primary chip" v-if="style.value == true">{{ getLabelForStyle(style.key) }}</md-chip>
        </div>
      </div>
      <transition name="fade">
        <div v-if="articles.length == 0 && !loading && !filter.onlyMyArticles">
          Nikt nie napisał jeszcze żadnego artykułu o wybranych kryteriach.
        </div>
        <div v-if="articles.length == 0 && !loading && filter.onlyMyArticles">
          Nie napisałeś jeszcze żadnego artykułu o wybranych kryteriach.
        </div>
      </transition>
      
      <div v-if="articles.length > 0">
        <div class="article" :key="article.id" v-for="article in articles">
          <article-short :article="article"></article-short>
        </div>
      </div>
      <div class="spinner-border text-primary" role="status" v-if="loading">
        <span class="sr-only">Loading...</span>
      </div>
    
    </div>

    <md-dialog-confirm
        :md-active.sync="newArticlesToLoad"
        md-title="Mamy coś nowego!"
        md-content="Ktoś właśnie napisał nowy artykuł."
        md-cancel-text="Później"
        @md-cancel="getCurrentCount"
        md-confirm-text="Chcę zobaczyć"
        @md-confirm="reloadPage" />
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
        ],
        page: 0,
        onlyMyArticles: false,
        userId: service.id,
        userArticles: false,
      },
      onlyMyArticlesImage: "",
      categories: categories.categories,
      writing_styles: writing_styles.writing_styles,
      busy: false,
      maxPage: 0,
      httpArticlesActive: false,
      httpPageActive: false,
      articlesCount: 0,
      newArticlesToLoad: false,
    }
  },
  methods: {
    getArticles(){
      this.$http.post('articles', this.filter).then(response => {
        if(response.body != null){
          this.articles = response.body;
          //this.filterArray();
          // if(this.onlyMyArticles == true){
          //   this.articlesFiltered = this.articlesFiltered.filter(function(article) {
          //     return article.userId == service.id;
          //   });
          //   this.onlyMyArticlesImage = service.avatar;
          // }
        }
        else this.articles = [];
        this.loading = false;
      });
      this.filter.page++;
    },
    appendToArticles(){
      if(this.filter.page <= this.maxPage && !this.httpArticlesActive){
        this.loading = true;
        this.httpArticlesActive = true;
        this.busy = true;
        this.$http.post('articles', this.filter).then(response => {
          if(response.body != null){
            for(var i = 0 ; i < response.body.length ; i++){
              this.articles.push(response.body[i]);
            }
            
          }
          // else this.articles = [];
          this.loading = false;
          this.httpArticlesActive = false;
          this.filter.page++;
          setTimeout(()=>{
            this.busy = false; 
        },1000);
        });
      
      }
      if(this.maxPage == -1){
        this.articles = [];
        this.loading = false;
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
    getMaxPageAndArticles(){
      if(this.httpPageActive == false){
        this.httpPageActive = true;
        this.$http.post('articles/maxPage', this.filter).then(response => {
          if(response.body != null) this.maxPage = response.body;
          else  this.maxPage = -1;

          this.httpPageActive = false;
          this.appendToArticles();
        });
      }
    },
    getNewCount(){
      this.$http.get('articles/count').then(response => {
        var newCount = 0;
        if(response.body != null){
          newCount = response.body;
        }
        else  newCount = 0;

        if(newCount > this.articlesCount){
          this.newArticlesToLoad = true;
        }
      });
    },
    getCurrentCount(){
      this.$http.get('articles/count').then(response => { //ile jest wszyskich artykulow
        if(response.body != null){
          this.articlesCount = response.body;
        }
        else  this.articlesCount = 0;
      });
    },
    reloadPage(){
      this.articles = [];
      EventBus.$emit('clear-filters');
      this.filter.onlyMyArticles = false;
      this.filter.page = 0;
      EventBus.$emit('give-me-filters');

      this.getCurrentCount();
    }
  },
  mounted(){
    if(this.$route.params.onlyMyArticles != null){
      this.filter.onlyMyArticles = this.$route.params.onlyMyArticles;
    }
    this.getCurrentCount();
    
    this.filter.userArticles = false;
    this.filter.userId = service.id;
    this.filter.page = 0;
    //this.getArticles();
    this.getNewArticlesInterval = setInterval(this.getNewCount, 10000);
    this.getMaxPageAndArticles();

    EventBus.$on('filter', ({category, loose, serious, funny}) => {
      this.loading = true;
      this.filter.category = category;
      this.filter.styles[0].value = loose;
      this.filter.styles[1].value = serious;
      this.filter.styles[2].value = funny;
      this.articles = [];
      this.filter.page = 0;
      this.busy = true;
      this.getMaxPageAndArticles();
      //this.appendToArticles();
      //this.getArticles();
      // this.filterArray();
      // if(this.onlyMyArticles){
      //   this.articlesFiltered = this.articlesFiltered.filter(function(article) {
      //     return article.userId == service.id;
      //   });
      // }
    });
    EventBus.$on('only-my-articles', () => {
      this.loading = true;
      this.filter.onlyMyArticles = true;
      this.articles = [];
      this.filter.page = 0;
      this.busy = true;
      this.getMaxPageAndArticles();
      //this.appendToArticles();
      //this.getArticles();
      // this.articlesFiltered = this.articlesFiltered.filter(function(article) {
      //   return article.userId == service.id;
      // });
    });
    EventBus.$on('all-articles', () => {
      this.loading = true;
      this.filter.onlyMyArticles = false;
      this.articles = [];
      this.filter.page = 0;
      this.busy = true;
      this.getMaxPageAndArticles();
      //this.appendToArticles();
      //this.getArticles();
      //this.filterArray();
    });

    EventBus.$emit('give-me-filters');
  },
  destroyed(){
    clearInterval(this.getNewArticlesInterval);
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
