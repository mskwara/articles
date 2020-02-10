<template>
  <div id="writing">
    <h3>Napisz nowy artykuł</h3>
    <form>
      <div class="form-group">
        <label for="title">Tytuł artykułu</label>
        <input type="text" class="form-control" id="title" aria-describedby="titleHelp" v-model="article.title">
        <small id="titleHelp" class="form-text text-muted">Podaj chwytny tytuł, który zaciekawi odbiorców.</small>
      </div>
      <div class="form-group">
        <label for="category">Kategoria</label>
        <select class="form-control" id="category" aria-describedby="category" v-model="article.category">
          <option :key="cat.key" v-for="cat in tags">{{cat.label}}</option>
        </select>
        <small id="category" class="form-text text-muted">Wybierz kategorię, do której można zaliczyć twój tekst</small>
      </div>
      <div class="form-group">
        <label for="content">Treść</label>
        <textarea class="form-control" id="content" rows="7" v-model="article.content"></textarea>
      </div>
      <button type="button" class="btn btn-primary publish" @click="publish()">Opublikuj</button>
    </form>

    <md-dialog-alert
      :md-active.sync="published"
      md-title="Opublikowano artykuł"
      :md-content="alertText" />
  </div>
</template>

<script>
import categories from "./categories.js";
import service from "./service.js";

export default {
  name: 'writing',
  components: {
  },
  data(){
    return {
      tags: categories.categories,
      article: {
        title: "",
        category: "",
        content: "",
        userId: 0,
      },
      published: false,
      alertText: ""
    }
  },
  mounted(){
    this.article.category = this.tags[0].label;
  },
  methods: {
    publish(){
      this.article.userId = service.id;
      for(var i = 0 ; i < this.tags.length ; i++){
        if(this.tags[i].label == this.article.category){
          this.article.category = this.tags[i].key;
          break;
        }
      }
      this.$http.post('articles/add', this.article);
      this.article.category = this.tags[0].label;
      this.article.content = "";
      this.alertText = "Twój artykuł <strong>"+this.article.title+"</strong> został pomyślnie opublikowany!."
      this.article.title = "";
      this.published = true;
    }
  },
}
</script>

<style scoped>
#writing {
  text-align: left;
}
h3 {
  margin-bottom: 20px;
}
</style>
