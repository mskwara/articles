<template>
  <div id="articleMain">
    <div class="spinner-border text-primary" role="status" v-if="loading">
      <span class="sr-only">Loading...</span>
    </div>
    <div class="media" v-if="!loading">
      <div class="container">
        <div class="info">
          <img v-if="image.length > 0" :src="image" class="mr-3 avatar">
          <img v-else src="./assets/avatar.png" class="mr-3 avatar">
          <div class="averageRating" v-if="articleRating != null">
            <h3 class="avg">{{averageRating}}</h3>
            <star-rating class="avgRating" :rating="parseFloat(averageRating)" read-only="true" :increment="0.01" :star-size="20" :show-rating="false"
               :border-width="4" border-color="#d8d8d8" :rounded-corners="true"
               :star-points="[23,2, 14,17, 0,19, 10,34, 7,50, 23,43, 38,50, 36,34, 46,19, 31,17]"></star-rating>
            <h6 class="avgCount" v-if="articleRating.count > 1">Na podstawie opinii {{articleRating.count}} osób</h6>
            <h6 class="avgCount" v-if="articleRating.count == 1">Na podstawie opinii {{articleRating.count}} osoby</h6>
          </div>
        </div>
        <div class="media-body">

          <div class="header">
            <h2 class="mt-0">{{article.title}}</h2>
            <h6>{{transformDate(article.date)}}</h6>
          </div>
          <p>{{article.content}}</p>
          <div class="bottom">
            <star-rating v-if="article.userId != getLoggedUserId()" class="rating" v-model="myRate.rating" @rating-selected="setRating"
              :star-size="30" :show-rating="false"
               :border-width="4" border-color="#d8d8d8" :rounded-corners="true"
               :star-points="[23,2, 14,17, 0,19, 10,34, 7,50, 23,43, 38,50, 36,34, 46,19, 31,17]"></star-rating>
          </div>
        </div>
      </div>
      <h4>Komentarze</h4>
      <div class="commentSection">
        <div class="comments" v-if="comments.length > 0">

          <ul class="list-group">
            <li class="list-group-item" :key="comment.id" v-for="comment in comments">
              <img v-if="getCommentImage(comment.userId).length > 0" :src="getCommentImage(comment.userId)" class="mr-3 commentAvatar">

              <img v-else src="./assets/avatar.png" class="mr-3 commentAvatar">
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
        <div class="nocomments" v-else>
          Bądź pierwszą osobą która zamieści komentarz!<br>
          <div class="spinner-grow text-primary" role="status" v-if="loadingNewComment">
            <span class="sr-only">Loading...</span>
          </div>
        </div>



        <div class="createComment">
          <div class="input-group mb-3">
            <textarea class="form-control" rows="2" placeholder="Napisz komentarz..." aria-describedby="button-addon2" v-model="newComment.content" />
            <div class="input-group-append">
              <button class="btn btn-primary" type="button" id="button-addon2" @click="addComment()">Wyślij</button>
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
      myRate: {
        id: null,
        articleId: null,
        userId: null,
        rating: 0,
      },
      articleRating: null,
      averageRating: null,
      image: "",
      commentsAvatars: [],
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
        if(response.body != null)  this.comments = response.body;
        else this.comments = [];

        var obj = {
          userIds: []
        };
        var isAssigned = false;
        for(var i = 0 ; i < this.comments.length ; i++){  //dla każdego komentarza
          isAssigned = false;
          for(var j = 0 ; j < obj.userIds.length ; j++){
            if(this.comments[i].userId == obj.userIds[j]){  //jeśli jest juz user w tablicy to pomiń
              isAssigned = true;
              break;
            }
          }
          if(isAssigned == false){    //a jak nie to wpisz go
            obj.userIds.push(this.comments[i].userId);
          }
        }
        this.$http.post('users/avatars-needed', obj).then(response => {
          this.commentsAvatars = response.body;
        });


        this.loadingNewComment = false;
      });
    },
    getAverageRating(){
      this.$http.get('articles/'+this.article.id+'/averageRating').then(response => {
        if(response.body != null){
          this.articleRating = response.body[0];
          var numeral = require('numeral');
          this.averageRating = numeral(this.articleRating.avg*1.0/this.articleRating.count).format('0.0');
        }
        else {
          this.articleRating = null;
          this.averageRating = "-";
        }
        var obj = {};
        obj.rating = this.averageRating;
        this.$http.put('articles/'+this.article.id+'/rating', obj);

      });
    },
    likeComment(e, comment){
      if(this.checkIfUserLikedAlready('comment',comment.id) == false){
        e.target.classList.toggle('likeDisabled');
        this.$http.put('comments/'+comment.id+'/incrementLikes');
        this.like.type = "comment";
        this.like.objId = comment.id;
        this.like.userId = service.id;
        this.$http.post('likes/add', this.like);
        this.$http.get('users/'+service.id+"/likes").then(response => {
          if(response.body != null) this.loggedUserLikes = response.body;
          else this.loggedUserLikes = [];

          this.getComments();
        });
      }
      else {
        e.target.classList.remove('likeDisabled');
        this.$http.put('comments/'+comment.id+'/decrementLikes');
        this.like.type = "comment";
        this.like.objId = comment.id;
        this.like.userId = service.id;
        this.$http.post('likes/delete', this.like);
        this.$http.get('users/'+service.id+"/likes").then(response => {
          if(response.body != null) this.loggedUserLikes = response.body;
          else this.loggedUserLikes = [];
          this.getComments();
        });
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
    setRating(rating) {
      this.myRate.rating = rating;
      if(this.myRate.id == null){
        this.$http.post('rating/add', this.myRate).then(()=>{
          this.getAverageRating();
        });
      }
      else {
        this.$http.put('rating/update', this.myRate).then(()=>{
          this.getAverageRating();
        });
      }
    },
    getCommentImage(userId){
      for(var i = 0 ; i < this.commentsAvatars.length ; i++){
        if(this.commentsAvatars[i].id == userId){
          return this.commentsAvatars[i].avatar;
        }
      }
      return "";
    },
    getLoggedUserId(){
      return service.id;
    }
  },
  mounted(){
    this.$http.get('articles/'+this.$route.params.id).then(response => {
      this.article = response.body[0];
      this.myRate.articleId = this.article.id;
      this.getAverageRating();
      this.$http.get('users/'+this.article.userId+'/avatar').then(response => {
        this.image = response.body[0].avatar;
      });
    });
    this.myRate.userId = service.id;
    this.$http.get('articles/'+this.$route.params.id+'/rating/user/'+service.id).then(response => {
      if(response.body != null){
        this.myRate.rating = response.body[0].rating;
        this.myRate.id = response.body[0].id;
      }
      else {
        this.myRate.rating = 0;
        this.myRate.id = null;
      }
    });

    this.getComments();
    this.getCommentsInterval = setInterval(this.getComments, 10000);
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
  min-width: 70px;
  margin: 0 !important;
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
.nocomments {
  width: 50%;
  float: left;
  text-align: left;
  margin-left: 20px;
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
  opacity: 0.4 !important;
}
.rating {
  margin-right: 30px;
}
.avgRating {
  justify-content: center;
  margin-bottom: 10px;
}
.info {
  display: flex;
  flex-direction: column;
  margin-right: 1rem;
}
.avg {
  margin: 20px 0 0 0;
  font-size: 40pt;
}
.avgCount {
  margin: 0;
  font-size: 9pt;
  font-style: italic;
}
</style>
