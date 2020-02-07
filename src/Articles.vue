<template>
  <div id="articles">
    <div class="spinner-border text-primary" role="status" v-if="loading">
      <span class="sr-only">Loading...</span>
    </div>
    <div v-if="articles.length > 0">
      <div class="article" :key="article.id" v-for="article in articles">
        <article-short :article="article"></article-short>
      </div>
    </div>
    <div v-if="articles.length == 0 && !loading">
      Nikt nie napisał jeszcze żadnego artykułu.
    </div>

  </div>
</template>

<script>
import ArticleShort from './ArticleShort.vue';

export default {
  name: 'articles',
  components: {
    ArticleShort
  },
  data(){
    return {
      articles: [],
      getArticlesInterval: null,
      loading: true,
    }
  },
  methods: {
    getArticles(){
      this.$http.get('articles').then(response => {
        this.articles = response.body;
        this.loading = false;
      });
    },
  },
  mounted(){
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
