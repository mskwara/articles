<template>
  <div id="writing">
    <div class="header">
      <h3>Edytujesz właśnie artykuł "<b>{{oldTitle}}</b>"</h3>
      <button type="button" class="btn btn-danger remove" @click="removing = true">Usuń artykuł</button>
    </div>
    <form class="form">
      <div class="form-group">
        <input type="text" class="form-control" id="title" aria-describedby="titleHelp" v-model="article.title" placeholder="Tytuł">
        <small id="titleHelp" class="form-text text-muted">Podaj chwytny tytuł, który zaciekawi odbiorców.</small>
      </div>
      <div class="container">
        <div class="left">
          <div class="form-group">
            <select class="custom-select" id="category" aria-describedby="category" v-model="article.tag">
              <option :key="cat.key" v-for="cat in categories" :value="cat.key">{{cat.label}}</option>
            </select>
            <small id="category" class="form-text text-muted">Wybierz kategorię, do której można zaliczyć twój tekst</small>
          </div>
          <div class="form-group select">
            <select class="custom-select" aria-describedby="writingStyle" v-model="article.style">
              <option :key="style.key" v-for="style in writing_styles" :value="style.key">{{style.label}}</option>
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
      <button type="button" class="btn btn-primary publish" @click="publish()">Zakończ edycję</button>
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

    <md-dialog-confirm
        :md-active.sync="removing"
        md-title="Czy na pewno chcesz usunąć?"
        md-content="Jeżeli usuniesz artykuł, nie będzie się już go dało odzyskać! Czy chcesz kontynuować?"
        md-cancel-text="Powrót"
        md-confirm-text="Usuń artykuł"
        @md-confirm="definitelyRemove" />
  </div>
</template>

<script>
import categories from "./categories.js";
import writing_styles from "./writing_styles.js";
import { EventBus } from './event-bus.js';

export default {
  name: 'writing',
  components: {
  },
  data(){
    return {
      categories: categories.categories,
      writing_styles: writing_styles.writing_styles,
      article: {},
      published: false,
      alertText: "",
      failed: false,
      oldTitle: "",
      removing: false,
    }
  },
  mounted(){
    this.article = this.$route.params.article;
    this.oldTitle = this.article.title;
  },
  methods: {
    publish(){
      if(this.article.title != "" && this.article.category != "Kategoria" && this.article.style != "Styl" && this.article.content != ""){
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
        this.$http.put('article/edit', this.article);
        this.alertText = "Twój artykuł <strong>"+this.oldTitle+"</strong> został pomyślnie zedytowany!."
        this.published = true;
      }
      else {
        this.failed = true;
      }
    },
    definitelyRemove(){
      this.$http.post('article/delete', this.article).then(()=>{
        var onlyMyArticles = false;
        this.$router.push({ name: 'articles', params: { onlyMyArticles: onlyMyArticles }});
        EventBus.$emit('all-articles');
      });
    }
  },
}
</script>

<style scoped>
#writing {
  text-align: left;
  animation-name: animationPop;
  animation-duration: 1s;
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
.header {
  display: flex;
  flex-direction: row;
  justify-content: space-between;
}
.remove {
  height: 40px;
}
@keyframes animationPop {
  from {opacity: 0}
  to {opacity: 1}
}
</style>
