<template>
  <div id="articlesfiltered">
    <div class="spinner-border text-primary" role="status" v-if="loading">
      <span class="sr-only">Loading...</span>
    </div>
    <div v-if="articles.length > 0">
      <div class="article" :key="article.id" v-for="article in articles">
        <article-short :article="article"></article-short>
      </div>
    </div>
    <div v-if="articles.length == 0 && !loading">
      Nikt nie napisał jeszcze żadnego artykułu w kategorii "{{category}}".
    </div>

  </div>
</template>

<script>
import ArticleShort from './ArticleShort.vue';
import categories from './categories.js';

export default {
  name: 'articlesfiltered',
  components: {
    ArticleShort
  },
  data(){
    return {
      articles: [],
      getArticlesInterval: null,
      loading: true,
      category: "",
      categories: categories.categories,
    }
  },
  methods: {
    getArticles(){
      this.$http.get('articles/category/'+this.$route.params.category).then(response => {
        if(response.body != null) this.articles = response.body;
        else this.articles = [];
        this.loading = false;
      });
    },
  },
  created(){
    for(var i = 0 ; i < this.categories.length ; i++){
      if(this.categories[i].key == this.$route.params.category){
        this.category = this.categories[i].label;
        break;
      }
    }
    this.getArticles();
    this.getArticlesInterval = setInterval(this.getArticles, 10000);
  },
  destroyed(){
    clearInterval(this.getArticlesInterval);
  }
}
</script>

<style scoped>
#articles {
  font-family: 'Avenir', Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
}
.article {
  margin-bottom: 30px;
}
</style>
