<template>
  <div id="articles">
    <div class="article" :key="article.id" v-for="article in articles">
      <article-short :article="article"></article-short>
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
      articles: {},
      getArticlesInterval: null,
    }
  },
  methods: {
    getArticles(){
      this.$http.get('articles').then(response => {
        this.articles = response.body;
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
