<template>
<div id="statistics">
  <div class="spinner-border text-primary" role="status" v-if="loading">
    <span class="sr-only">Loading...</span>
  </div>
  <div v-if="!loading">
    <h3>Twoje statystyki</h3>
    <div class="container">
      <div class="ratingArea">
        <p class="info">Tak inni Cię oceniali</p>
        <h1 class="averageRating">{{averageRating}}</h1>
        <star-rating class="avgRating" :rating="parseFloat(averageRating)" :read-only="true" :increment="0.01" :star-size="20" :show-rating="false"
           :border-width="4" border-color="#d8d8d8" :rounded-corners="true"
           :star-points="[23,2, 14,17, 0,19, 10,34, 7,50, 23,43, 38,50, 36,34, 46,19, 31,17]"></star-rating>

        <div class="rate" :key="rate.id" v-for="rate in globalRating.rating">
          {{rate.id}}
          <div class="progress progressRate">
            <div class="progress-bar bg-success" role="progressbar" :style="getRateProgressStyle(rate.id, rate.count)" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
          </div>
        </div>
      </div>
      <div class="articlesStats">
        % Twoich artykułów
        <div class="progress progressPercent">
          <div class="progress-bar bg-success" role="progressbar" :style="getArticleProgressStyle(globalRating.articles.yourArticles, globalRating.articles.allArticles)" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
        % Twoich komentarzy
        <div class="progress progressPercent">
          <div class="progress-bar bg-warning" role="progressbar" :style="getArticleProgressStyle(globalRating.comments.yourComments, globalRating.comments.allComments)" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
          <div class="statsInfo" v-if="globalRating.articles.yourArticles >= 5">Napisałeś <b>{{globalRating.articles.yourArticles}}</b> artykułów.</div>
          <div class="statsInfo" v-if="globalRating.articles.yourArticles >= 2 && globalRating.articles.yourArticles <= 4">Napisałeś <b>{{globalRating.articles.yourArticles}}</b> artykuły.</div>
          <div class="statsInfo" v-if="globalRating.articles.yourArticles == 1">Napisałeś <b>{{globalRating.articles.yourArticles}}</b> artykuł.</div>
          <div class="statsInfo" v-if="globalRating.articles.yourArticles == 0">Nie napisałeś jeszcze artykułu.</div>

          <div class="statsInfo" v-if="globalRating.comments.yourComments >= 5">Zamieściłeś <b>{{globalRating.comments.yourComments}}</b> komentarzy.</div>
          <div class="statsInfo" v-if="globalRating.comments.yourComments >= 2 && globalRating.articles.yourArticles <= 4">Zamieściłeś <b>{{globalRating.comments.yourComments}}</b> komentarze.</div>
          <div class="statsInfo" v-if="globalRating.comments.yourComments == 1">Zamieściłeś <b>{{globalRating.comments.yourComments}}</b> komentarz.</div>
          <div class="statsInfo" v-if="globalRating.comments.yourComments == 0">Nic jeszcze nie skomentowałeś.</div>

          <div class="statsInfo" v-if="globalRating.yourRatings >= 5">Oceniłeś <b>{{globalRating.yourRatings}}</b> artykułów.</div>
          <div class="statsInfo" v-if="globalRating.yourRatings >= 2 && globalRating.articles.yourArticles <= 4">Oceniłeś <b>{{globalRating.yourRatings}}</b> artykuły.</div>
          <div class="statsInfo" v-if="globalRating.yourRatings == 1">Oceniłeś <b>{{globalRating.yourRatings}}</b> artykuł.</div>
          <div class="statsInfo" v-if="globalRating.yourRatings == 0">Nie oceniłeś jeszcze żadnego artykułu.</div>
      </div>



    </div>
  </div>
</div>
</template>

<script>
import service from './service.js';

export default {
  name: 'statistics',
  components: {
  },
  data(){
    return {
      globalRating: [],
      averageRating: null,
      ileOcen: 0,
      loading: true,
    }
  },
  mounted(){
    this.$http.get('users/'+service.id+'/articles/statistics').then(response => {
      this.globalRating = response.body;
      for(var i = 0 ; i < 5 ; i++){
        this.averageRating += (5-i) * this.globalRating.rating[i].count;
        this.ileOcen += this.globalRating.rating[i].count;
      }
      var numeral = require('numeral');
      this.averageRating = numeral(this.averageRating*1.0/this.ileOcen).format('0.0');
      if(this.averageRating == 0.0) this.averageRating = "--";
      this.loading = false;
    });
  },
  methods: {
    getRateProgressStyle(id, count){
      var numeral = require('numeral');
      var frac = numeral(count/this.ileOcen).format('0.00');
      var wynik = numeral(frac * 100).format('0');
      var style = "width: "+wynik+"% ; ";
      if(id == 5) style += "background-color: #00b803 !important";
      if(id == 4) style += "background-color: #00b86b !important";
      if(id == 3) style += "background-color: #fae902 !important";
      if(id == 2) style += "background-color: #fa9302 !important";
      if(id == 1) style += "background-color: #fa3402 !important";
      return style;
    },
    getArticleProgressStyle(your, all){
      var numeral = require('numeral');
      var frac = numeral(your/all).format('0.00');
      var wynik = numeral(frac * 100).format('0');
      var style = "width: "+wynik+"%";
      return style;
    }
  },
}
</script>

<style scoped>
#profile {
  text-align: center;
}
.container {
  display: flex;
  flex-direction: row;
  justify-content: center;
}
.ratingArea {
  border: 1px solid #b3b3b3;
  border-radius: 10px;
  width: 300px;
  padding: 10px;
}
.articlesStats {
  border: 1px solid #b3b3b3;
  border-radius: 10px;
  width: 400px;
  padding: 10px;
  margin-left: 20px;
}
.averageRating {
  font-size: 60pt;
  margin-bottom: 0;
}
.avgRating {
  justify-content: center;
  margin: 0 0 20px 0;

}
.info {
  margin-bottom: 0;
}
.progressRate {
  margin-bottom: 10px;
  width: 95% !important;
}
.progressPercent {
  margin-bottom: 10px;
  width: 100% !important;
}
.rate {
  display: flex;
  flex-direction: row;
  flex-wrap: nowrap;
  justify-content: space-between;
}
h3 {
  margin-bottom: 30px;
}
.statsInfo {
  font-size: 12pt;
  text-align: left;
  margin-top: 30px;
  margin-left: 10px;
}
</style>
