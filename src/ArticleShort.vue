<template>
  <div id="article">
    <div class="media">
      <img src="./assets/avatar.jpg" class="mr-3 avatar">
      <div class="media-body">
        <div class="header">
          <h2 class="mt-0">{{article.title}}</h2>
          <h6>{{article.date}}</h6>
        </div>
        <p>{{article.content}}</p>
        <div class="bottom">
          <button type="button" class="btn btn-primary" @click="goToArticle(article.id)">Read more</button>

          <div class="likeCom">
            <span class="comment"><img src="./assets/comment.svg" /><md-tooltip md-direction="left">{{article.commentsNumber}} comments</md-tooltip></span>
            <span class="like"><img src="./assets/like.svg" /><md-tooltip md-direction="right">{{article.likes}} likes</md-tooltip></span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>

export default {
  name: 'article',
  components: {
  },
  props: {
    article: Object
  },
  data(){
    return {
    }
  },
  methods: {
    goToArticle(id){
      this.$router.push("article/"+id);
    },
  },
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
}
p {
  margin-left: 40px;
  overflow: hidden;
   text-overflow: ellipsis;
   display: -webkit-box;
   -webkit-box-orient: vertical;
   -webkit-line-clamp: 6; /* number of lines to show */
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
.likeCom {
  width: 150px;
  display: flex;
  flex-direction: row;
  align-items: flex-end;
  font-size: 11pt;
}
</style>
