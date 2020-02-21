<template>
<div v-infinite-scroll="appendToArticles" infinite-scroll-disabled="busy" infinite-scroll-distance="10">
  <div id="user">
    <div class="spinner-border text-primary" role="status" v-if="loading">
      <span class="sr-only">Loading...</span>
    </div>
    <div class="page" v-if="!loading">
      <h3>Profil użytkownika {{user.name}} {{user.surname}}</h3>
      <div class="info">
        <div class="image">
          <img v-if="user.avatar.length > 0" class="avatar" :src="user.avatar">
          <img v-else src="./assets/avatar.png" class="mr-3 avatar">
        </div>
        <stats-component :userId="filter.userId" :current="false"></stats-component>
      </div>
      <div class="description" v-if="!waitWithDescription && user.description != ''">
        {{user.description}}
      </div>
      <div class="description" v-if="!waitWithDescription && user.description == ''">
        Brak opisu...
      </div>
      <h3 style="text-align: left ; width: 100%" v-if="!waitWithDescription">Napisane artykuły</h3>
      
      <div v-if="articles.length > 0 && !waitWithDescription">
        <div class="article" :key="article.id" v-for="article in articles">
          <article-short :article="article"></article-short>
        </div>
      </div>
      
      <div class="spinner-border text-primary" role="status" v-if="loadingArticles">
        <span class="sr-only">Loading...</span>
      </div>
    </div>
  </div>
</div>
</template>

<script>
import StatsComponent from './StatsComponent.vue';
import ArticleShort from './ArticleShort.vue';
import { EventBus } from './event-bus.js';

export default {
  name: 'user',
  components: {
    StatsComponent, ArticleShort
  },
  data(){
    return {
      user: {},
      loading: true,
      waitWithDescription: true,
      loadingArticles: true,
      articles: [],
      filter: {
        userArticles: true,
        userId: null,
        page: 0,
      },
      busy: false,
      maxPage: 0,
      httpArticlesActive: false,
      httpPageActive: false,
    }
  },
  mounted(){
    this.filter.userId = this.$route.params.userId;
    this.$http.get('users/info/'+this.filter.userId).then(response => {
      this.user = response.body[0];
      this.loading = false;
    });

    this.filter.page = 0;
    this.filter.userArticles = true;

    this.getMaxPageAndArticles();
  
    
    EventBus.$on('loadedStats', () => {
      this.waitWithDescription = false;
    });

  },
  methods: {
    appendToArticles(){
      if(this.filter.page <= this.maxPage && !this.httpArticlesActive){
        this.loadingArticles = true;
        this.httpArticlesActive = true;
        this.busy = true;
        this.$http.post('articles', this.filter).then(response => {
          if(response.body != null){
            for(var i = 0 ; i < response.body.length ; i++){
              this.articles.push(response.body[i]);
            }
            
          }
          // else this.articles = [];
          this.loadingArticles = false;
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
  },
}
</script>

<style scoped>

.info {
  display: flex;
  flex-direction: row;
}
.avatar {
  width: 300px;
  min-width: 300px;
  height: 300px;
  min-height: 300px;
  border-radius: 150px;
  flex-grow: 1;
}
.description {
  border: 1px solid #b3b3b3;
  border-radius: 10px;
  text-align: left;
  padding: 20px;
  width: 60%;
  min-width: 300px;
  margin-top: 20px;
  margin-bottom: 30px;
  align-self: center;

  animation-name: moveIn;
  animation-duration: 2s;
}
.page {
  display: flex;
  flex-direction: column;
  align-items: center;
}
.article {
  margin: 30px 0;
}
img {
  animation-name: animationPop;
  animation-duration: 1s;
}
h3 {
  animation-name: animationPop;
  animation-duration: 1s;
  offset: 30px 0;
}
@keyframes animationPop {
  from {opacity: 0}
  to {opacity: 1}
}
@keyframes moveIn {
  from {opacity: 0; translate: -100px 0}
  to {opacity: 1; translate: 0 0}
}
</style>
