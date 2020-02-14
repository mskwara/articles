<template>
  <div id="articleName">
    <div class="media">
      <img v-if="image.length > 0" :src="image" class="mr-3 avatar">
      <img v-else src="./assets/avatar.png" class="mr-3 avatar">
      <div class="media-body">
        <div class="header">
          <h2 class="mt-0">{{article.title}}</h2>
          <star-rating v-if="article.rating != 0.0" class="avgRating" :rating="parseFloat(article.rating)" :read-only="true"
            :increment="0.01" :star-size="20" :show-rating="false"
             :border-width="4" border-color="#d8d8d8" :rounded-corners="true"
             :star-points="[23,2, 14,17, 0,19, 10,34, 7,50, 23,43, 38,50, 36,34, 46,19, 31,17]"></star-rating>
          <h6>{{transformDate(article.date)}}</h6>
        </div>
        <p>{{article.content}}</p>
        <div class="bottom">
          <button type="button" class="btn btn-primary" @click="goToArticle(article.id)">Czytaj dalej</button>

          <div class="comment" v-if="article.commentsNumber > 0">
            <img src="./assets/comment.svg" />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>

export default {
  name: 'articleName',
  components: {
  },
  props: {
    article: Object
  },
  data(){
    return {
      image: "",
    }
  },
  mounted(){
    this.$http.get('users/'+this.article.userId+'/avatar').then(response => {
      this.image = response.body[0].avatar;
    });
  },
  methods: {
    goToArticle(id){
      this.$router.push({ name: 'article', params: { id: id }});
    },
    transformDate(date){
      var day = parseInt(date.slice(8,10));
      var monthTable = ["stycznia", "lutego", "marca", "kwietnia", "maja", "czerwca", "lipca", "sierpnia", "września", "października", "listopada", "grudnia"];
      var month = monthTable[parseInt(date.slice(5,7))-1];
      var year = date.slice(0,4);
      var time = date.slice(11,16);
      var tDate = day+" "+month+" "+year+", "+time;
      return tDate;
    }
  },
}
</script>

<style scoped>
#articleName {
  font-family: 'Avenir', Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
}
.media {
  border: 1px solid gray;
  border-radius: 5px;
  padding: 20px;
}
h2 {
  text-align: left;
}
h6 {
  font-size: 10pt;
}
.avatar {
  border-radius: 200px;
  width: 200px;
  min-width: 50px;
}
p {
  margin-left: 40px;
  overflow: hidden;
   text-overflow: ellipsis;
   display: -webkit-box;
   -webkit-box-orient: vertical;
   -webkit-line-clamp: 6; /* number of lines to show */
   word-wrap: break-word;
   max-width: 800px;
   min-width: 500px;
}
.header {
  display: flex;
  flex-direction: row;
  justify-content: space-between;
}
button {
  float: left;
}
.like {
  width: 35px;
  margin-right: 20px;
  margin-left: 5px;
  padding: 5px;
}
.comment {
  width: 30px;
  margin-right: 20px;
  padding: 5px;
  margin-left: 5px;
}
.bottom {
  display: flex;
  flex-direction: row;
  justify-content: space-between;
}
.avgRating {
  margin: 3px 0 0 20px;
  padding: 0;
  align-items: flex-start !important;
  flex: 1;
}
</style>
