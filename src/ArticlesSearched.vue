<template>
  <div id="articlessearched">
    <md-chip class="md-accent chip">{{ phrase }}</md-chip>
    <div class="spinner-border text-primary" role="status" v-if="loading">
      <span class="sr-only">Loading...</span>
    </div>
    <div v-if="articles.length > 0">
      <div class="article" :key="article.id" v-for="article in articles">
        <article-short :article="article"></article-short>
      </div>
    </div>
    <div v-if="articles.length == 0 && !loading">
      Nie znaleziono frazy "{{phrase}}" w żadnym artykule.
    </div>

  </div>
</template>

<script>
import ArticleShort from './ArticleShort.vue';

export default {
  name: 'articlessearched',
  components: {
    ArticleShort
  },
  data(){
    return {
      articles: [],
      getArticlesInterval: null,
      loading: true,
      phrase: "",
    }
  },
  methods: {
    getArticles(){
      this.$http.get('articles/search/'+this.phrase).then(response => {
        if(response.body != null) this.articles = response.body;
        else this.articles = [];
        this.loading = false;
      });
    },
  },
  created(){
    this.phrase = this.$route.params.phrase;
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
.chip {
  margin-bottom: 10px;
  text-transform: capitalize;
}
</style>
