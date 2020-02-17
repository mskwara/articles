<template>
  <div id="writing">
    <h3>Napisz nowy artykuł</h3>
    <form class="form">
      <div class="form-group">
        <input type="text" class="form-control" id="title" aria-describedby="titleHelp" v-model="article.title" placeholder="Tytuł">
        <small id="titleHelp" class="form-text text-muted">Podaj chwytny tytuł, który zaciekawi odbiorców.</small>
      </div>
      <div class="container">
        <div class="left">
          <div class="form-group">
            <select class="custom-select" id="category" aria-describedby="category" v-model="article.category">
              <option selected disabled>Kategoria</option>
              <option :key="cat.key" v-for="cat in categories">{{cat.label}}</option>
            </select>
            <small id="category" class="form-text text-muted">Wybierz kategorię, do której można zaliczyć twój tekst</small>
          </div>
          <div class="form-group select">
            <select class="custom-select" aria-describedby="writingStyle" v-model="article.style">
              <option selected disabled>Styl</option>
              <option :key="style.key" v-for="style in writing_styles">{{style.label}}</option>
            </select>
            <small id="writingStyle" class="form-text text-muted">Wybierz styl, w jakim będzie napisany ten artykuł</small>
          </div>
          <div class="form-group">
            <textarea class="form-control" id="content" rows="8" v-model="article.bibliography" placeholder="Bibliografia"></textarea>
            <small id="bibliographyHelp" class="form-text text-muted">Jeśli chcesz, powiedz z czego korzystałeś</small>
          </div>
        </div>
        <div class="right">
          <div class="form-group">
            <textarea class="form-control" id="content" rows="15" v-model="article.content" placeholder="Treść artykułu"></textarea>
            <small id="contentHelp" class="form-text text-muted">Nie podpisuj się, zrobimy to za Ciebie!</small>
          </div>
        </div>
      </div>
      <button type="button" class="btn btn-primary publish" @click="publish()">Opublikuj</button>
    </form>

    <md-dialog-alert class="success"
      :md-active.sync="published"
      md-title="Opublikowano artykuł"
      :md-content="alertText" />

    <md-dialog-alert class="error"
        :md-active.sync="failed"
        md-title="Coś poszło nie tak..."
        md-content="Wypełnij wszystkie pola przed publikacją!"
        md-confirm-text="Zamknij" />
  </div>
</template>

<script>
import categories from "./categories.js";
import writing_styles from "./writing_styles.js";
import service from "./service.js";

export default {
  name: 'writing',
  components: {
  },
  data(){
    return {
      categories: categories.categories,
      writing_styles: writing_styles.writing_styles,
      article: {
        title: "",
        category: "",
        style: "",
        content: "",
        bibliography: "",
        userId: 0,
      },
      published: false,
      alertText: "",
      failed: false
    }
  },
  mounted(){
    this.article.category = "Kategoria";
    this.article.style = "Styl";
  },
  methods: {
    publish(){
      if(this.article.title != "" && this.article.category != "Kategoria" && this.article.style != "Styl" && this.article.content != ""){
        this.article.userId = service.id;
        for(var i = 0 ; i < this.categories.length ; i++){
          if(this.categories[i].label == this.article.category){    //zamiana z label na key
            this.article.category = this.categories[i].key;
            break;
          }
        }
        for(var j = 0 ; j < this.writing_styles.length ; j++){
          if(this.writing_styles[j].label == this.article.style){
            this.article.style = this.writing_styles[j].key;
            break;
          }
        }
        this.$http.post('articles/add', this.article);
        this.article.category = "Kategoria";
        this.article.style = "Styl";
        this.article.content = "";
        this.article.bibliography = "";
        this.alertText = "Twój artykuł <strong>"+this.article.title+"</strong> został pomyślnie opublikowany!."
        this.article.title = "";
        this.published = true;
      }
      else {
        this.failed = true;
      }
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
.error {
  border: 2px solid red !important;
  border-radius: 10px;
}
.success {
  border: 2px solid green !important;
  border-radius: 10px;
}
small {
  margin-bottom: 0px !important;
}
.left {
  flex-grow: 1;
  margin-right: 15px;
}
.right {
  flex-grow: 5;
  margin-left: 15px;
}
.container {
  display: flex;
  flex-direction: row;
  padding: 0;
  margin: 0;
  max-width: 100% !important;
}
.form {
  display: flex;
  flex-direction: column;
}
</style>
