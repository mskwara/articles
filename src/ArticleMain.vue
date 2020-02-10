<template>
  <div id="articleMain">
    <div class="spinner-border text-primary" role="status" v-if="loading">
      <span class="sr-only">Loading...</span>
    </div>
    <div class="media" v-if="!loading">
      <div class="container">
        <img src="./assets/avatar.jpg" class="mr-3 avatar">
        <div class="media-body">

          <div class="header">
            <h2 class="mt-0">{{article.title}}</h2>
            <h6>{{transformDate(article.date)}}</h6>
          </div>
          <p>{{article.content}}</p>
          <div class="bottom">
            <button type="button" class="btn btn-primary like" @click="likeArticle()" :disabled="checkIfUserLikedAlready('article', article.id)" onclick="this.disabled = true"><img class="likeIcon" src="./assets/likeWhite.svg" />Like</button>
          </div>
        </div>
      </div>
      <h4>Komentarze</h4>
      <div class="commentSection">
        <div class="comments" v-if="comments != null">

          <ul class="list-group">
            <li class="list-group-item" :key="comment.id" v-for="comment in comments">
              <img src="./assets/avatar.jpg" class="mr-3 commentAvatar">
              <div class="comContent">
                <h6>
                  <a class="username">{{comment.userName}} {{comment.userSurname}}</a>
                  {{comment.content}}
                </h6>
                <div class="likeIconComments">
                  <img src="./assets/like.svg" @click="likeComment($event, comment)" :class="checkIfUserLikedAlready('comment',comment.id) ? 'likeDisabled' : ''" />
                  <md-tooltip md-direction="right">{{comment.likes}} likes</md-tooltip>
                </div>
              </div>

            </li>
          </ul>
          <div class="spinner-grow text-primary" role="status" v-if="loadingNewComment">
            <span class="sr-only">Loading...</span>
          </div>
        </div>


        <div class="createComment">
          <div class="input-group mb-3">
            <textarea class="form-control" rows="2" placeholder="Write a comment..." aria-describedby="button-addon2" v-model="newComment.content" />
            <div class="input-group-append">
              <button class="btn btn-primary" type="button" id="button-addon2" @click="addComment()">Send</button>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</template>

<script>
import service from "./service.js";

export default {
  name: 'articleMain',
  components: {
  },
  data(){
    return {
      article: {},
      comments: [],
      newComment: {
        articleId: 0,
        content: "",
        userId: 0,
        userName: "",
        userSurname: "",
      },
      getCommentsInterval: null,
      loading: true,
      loadingNewComment: false,
      like: {
        type: "",
        objId: null,
        userId: null,
      },
      loggedUserLikes: [],
    }
  },
  methods: {
    addComment(){
      this.loadingNewComment = true;
      this.newComment.articleId = this.$route.params.id;
      this.newComment.userId = service.id;
      this.newComment.userName = service.name;
      this.newComment.userSurname = service.surname;
      this.$http.post('comments/add', this.newComment);
      this.newComment.content = "";
      this.getComments();
      this.$http.put('articles/'+this.$route.params.id+'/commentsNumber');
    },
    getComments(){
      this.$http.get('articles/'+this.$route.params.id+'/comments').then(response => {
        this.comments = response.body;

        this.loadingNewComment = false;
      });
    },
    likeArticle(){
      this.$http.put('articles/'+this.$route.params.id+'/likes');
      this.like.type = "article";
      this.like.objId = this.article.id;
      this.like.userId = service.id;
      this.$http.post('likes/add', this.like);
      this.$http.get('users/'+service.id+"/likes").then(response => {
        this.loggedUserLikes = response.body;
      });
    },
    likeComment(e, comment){
      e.target.classList.toggle('likeDisabled');
      if(!this.checkIfUserLikedAlready('comment',comment.id)){
        this.$http.put('comments/'+comment.id+'/likes');
        this.like.type = "comment";
        this.like.objId = comment.id;
        this.like.userId = service.id;
        this.$http.post('likes/add', this.like);
        this.$http.get('users/'+service.id+"/likes").then(response => {
          this.loggedUserLikes = response.body;
        });
        this.getComments();
      }
    },
    checkIfUserLikedAlready(type, objId){
      for(var i = 0; i < this.loggedUserLikes.length ; i++){
        if(this.loggedUserLikes[i].type == type && this.loggedUserLikes[i].objId == objId){
          return true;
        }
      }
      return false;
    },
    transformDate(date){
      var day = parseInt(date.slice(8,10));
      var monthTable = ["stycznia", "lutego", "marca", "kwietnia", "maja", "czerwca", "lipca", "sierpnia", "września", "października", "listopada", "grudnia"];
      var month = monthTable[parseInt(date.slice(5,7))-1];
      var year = date.slice(0,4);
      var time = date.slice(11,16);
      var tDate = day+" "+month+" "+year+", "+time;
      return tDate;
    },
  },
  mounted(){
    this.$http.get('articles/'+this.$route.params.id).then(response => {
      this.article = response.body[0];
    });
    this.getComments();
    this.getCommentsInterval = setInterval(this.getComments, 5000);
    this.$http.get('users/'+service.id+"/likes").then(response => {
      if(response.body != null){
        this.loggedUserLikes = response.body;
      }
      else this.loggedUserLikes = [];
      this.loading = false;
    });
  },
  destroyed(){
    clearInterval(this.getCommentsInterval);
  }
}
</script>

<style scoped>
.media {
  border: 1px solid gray;
  border-radius: 5px;
  padding: 20px;
  display: flex;
  flex-direction: column;
}
.container {
  display: flex;
  flex-direction: row;
  align-items: flex-start;
}
h2 {
  text-align: left;
}
h4 {
  text-align: left;
  margin-top: 30px;
}
h6 {
  font-size: 10pt;
  margin-right: 30px;
}
.avatar {
  border-radius: 200px;
  width: 200px;
}
.commentAvatar {
  border-radius: 30px;
  width: 30px;
  float: left;
  margin-bottom: 10px;
}
.username {
  color: #007bff !important;
  font-weight: bold;
  margin-right: 10px;
  cursor: pointer;
}
p {
  margin-left: 20px;
  white-space: pre-line;
}
.header {
  display: flex;
  flex-direction: row;
  justify-content: space-between;
}
button {
  margin-right: 20px;
}
.like {
  display: flex;
  flex-direction: row;
}
.likeIcon {
  width: 20px !important;
  height: 20px !important;
  margin-right: 5px;
}
.likeIconComments {
  width: 20px !important;
  min-width: 20px !important;
  height: 20px !important;
  min-height: 20px !important;
  margin-right: 5px;
  float: right;
  cursor: pointer;
}
.comContent {
  display: flex;
  flex-direction: row;
  justify-content: space-between;
}
.comments {
  width: 50%;
  float: left;
}
.createComment {
  width: 40%;
  float: right;
}
.commentSection {
  display: flex;
  flex-direction: row;
  flex-wrap: nowrap;
  justify-content: space-between;
  width: 100%;
}
.date {
  font-size: 6pt;
}
.bottom {
  display: flex;
  flex-direction: row;
  justify-content: flex-end;
  align-items: center;
}
.likeDisabled {
  opacity: 30%;
}
</style>
