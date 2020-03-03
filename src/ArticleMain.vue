<template>
  <div id="articleMain">
    <div class="spinner-border text-primary" role="status" v-if="loadingArticle">
      <span class="sr-only">Loading...</span>
    </div>
    <div class="media" v-if="!loadingArticle">
      <div class="container">
        <div class="info">
          <div class="spinner-border text-success loadingImage" role="status" v-if="loadingImage">
            <span class="sr-only">Loading...</span>
          </div>
          <div id="image" v-if="!loadingImage">
            <img v-if="image.length > 0" :src="image" class="mr-3 avatar" @click="goToUserInfo(article.article.userId)">
            <img v-else src="./assets/avatar.png" class="mr-3 avatar" @click="goToUserInfo(article.article.userId)">
          </div>
          <div class="spinner-border text-primary" style="align-self: center ; margin-top: 10px" role="status" v-if="loadingAverageRating && article.article.ratingDisabled == 0">
            <span class="sr-only">Loading...</span>
          </div>
          <div class="averageRating" v-if="articleRating != null && !loadingAverageRating && article.article.ratingDisabled == 0">
            <h3 class="avg">{{averageRating}}</h3>
            <star-rating class="avgRating" :rating="parseFloat(averageRating)" :read-only="true" :increment="0.01" :star-size="20" :show-rating="false"
               :border-width="4" border-color="#d8d8d8" :rounded-corners="true"
               :star-points="[23,2, 14,17, 0,19, 10,34, 7,50, 23,43, 38,50, 36,34, 46,19, 31,17]"></star-rating>
            <h6 class="avgCount" v-if="articleRating.count > 1">Na podstawie opinii {{articleRating.count}} osób</h6>
            <h6 class="avgCount" v-if="articleRating.count == 1">Na podstawie opinii {{articleRating.count}} osoby</h6>
          </div>
        </div>
        <div class="media-body">

          <div class="header">
            <h2 class="mt-0">{{article.article.title}}</h2>
            <div class="topRight">
              <a class="edit" v-if="article.article.userId == getLoggedUserId()" @click="goToEditing()">Edytuj</a>
              <h6>{{transformDate(article.article.date)}}</h6>
            </div>
          </div>
          <p>{{article.article.content}}</p>
          <p class="podpis">{{article.userInfo.name}} {{article.userInfo.surname}}</p>
          <div class="bibliography" v-if="article.article.bibliography != ''">
            <a @click="toggleBibliography()">{{showBibliographyText}}</a>
            <transition name="fade">
              <p v-if="isBibliographyVisible == true">{{article.article.bibliography}}</p>
            </transition>
          </div>
          <div class="spinner-border text-primary" role="status" v-if="loadingRating && article.article.userId != getLoggedUserId()">
            <span class="sr-only">Loading...</span>
          </div>
          <div class="bottom" v-if="!loadingRating && article.article.ratingDisabled == 0">
            <star-rating v-if="article.article.userId != getLoggedUserId()" class="rating" v-model="myRate.rating" @rating-selected="setRating"
              :star-size="30" :show-rating="false" :read-only="addingNewRating"
               :border-width="4" border-color="#d8d8d8" :rounded-corners="true"
               :star-points="[23,2, 14,17, 0,19, 10,34, 7,50, 23,43, 38,50, 36,34, 46,19, 31,17]"></star-rating>
          </div>
        </div>
      </div>
      <h4 id="komentarze">Komentarze</h4>
      <div class="spinner-border text-primary" role="status" v-if="loadingComments">
        <span class="sr-only">Loading...</span>
      </div>
      <div class="commentSection" v-if="!loadingComments">
        <div class="comOrNotCom">
          <transition name="fade">
          <div class="comments" v-if="comments.length > 0">
              <div class="spinner-grow text-primary" role="status" v-if="loadingNewComment">
                <span class="sr-only">Loading...</span>
              </div>
            <ul class="list-group">
              <transition-group name="fade">
              <li class="list-group-item" :key="comment.id" v-for="comment in comments">
                <div class="avatarWithTooltip">
                  <img v-if="getCommentImage(comment.userId).length > 0" :src="getCommentImage(comment.userId)" class="mr-3 commentAvatar">
                  <img v-else src="./assets/avatar.png" class="mr-3 commentAvatar">
                  <md-tooltip md-direction="left">{{transformDate(comment.date)}}</md-tooltip>
                </div>
                <div class="comContent">
                  <h6 class="inCommentH6">
                    <a class="username" @click="goToUserInfo(comment.userId)">{{comment.userName}} {{comment.userSurname}}</a>
                    {{comment.content}}
                  </h6>
                  <div class="likeIconComments">
                    <img src="./assets/like.svg" @click="likeComment($event, comment)" :class="checkIfUserLikedAlready('comment',comment.id) ? 'likeDisabled' : 'likeEnabled'" />
                    <h5 class="numberOfLikes">{{comment.likes}}</h5>
                  </div>
                  <div class="deleteComment" v-if="comment.userId == getLoggedUserId()">
                    <img src="./assets/cancel.svg" @click="deleteComment($event, comment)" />
                  </div>
                </div>
              </li>
              </transition-group>
            </ul>
          </div>
          </transition>
          <transition name="fadeDelay">
          <div class="nocomments" v-if="comments.length == 0">
            Bądź pierwszą osobą która zamieści komentarz!<br>
            <div class="spinner-grow text-primary" role="status" v-if="loadingNewComment">
              <span class="sr-only">Loading...</span>
            </div>
          </div>
          </transition>
        </div>



        <div class="createComment">
          <div class="input-group mb-3">
            <textarea class="form-control" rows="2" placeholder="Napisz komentarz..." aria-describedby="button-addon2" v-model="newComment.content" />
            <div class="input-group-append">
              <button class="btn btn-success" type="button" id="button-addon2" @click="addComment()" :disabled="newComment.content == ''">Wyślij</button>
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
      loadingArticle: true,
      loadingRating: true,
      loadingAverageRating: true,
      loadingComments: true,
      loadingImage: true,
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
      showBibliographyText: "Bibliografia",
      isBibliographyVisible: false,
      addingNewRating: false,
      togglingLike: false,
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
        if(this.loadingComments == true){
          this.loadingComments = false;
          this.getCommentsInterval = setInterval(this.getComments, 10000);
        }
        this.loadingNewComment = false;
      });
    },
    getAverageRating(){
      this.$http.get('articles/'+this.article.article.id+'/averageRating').then(response => {
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
        this.$http.put('articles/'+this.article.article.id+'/rating', obj);
        this.loadingAverageRating = false;
      });
    },
    likeComment(e, comment){
      if(this.togglingLike == false){
        this.like.type = "comment";
        this.like.objId = comment.id;
        this.like.userId = service.id;
        this.togglingLike = true;
        if(this.checkIfUserLikedAlready('comment',comment.id) == false){
          e.target.classList.remove('likeEnabled');
          e.target.classList.toggle('likeDisabled');
          comment.likes++;
          this.$http.put('comments/'+comment.id+'/incrementLikes').then(() => {
            this.$http.post('likes/add', this.like).then(()=>{
              this.$http.get('users/'+service.id+"/likes").then(response => {
                if(response.body != null) this.loggedUserLikes = response.body;
                else this.loggedUserLikes = [];
                this.togglingLike = false;
                this.getComments();
              });
            });
          });
        }
        else {
          e.target.classList.remove('likeDisabled');
          e.target.classList.toggle('likeEnabled');
          comment.likes--;
          this.$http.put('comments/'+comment.id+'/decrementLikes').then(() => {
            this.$http.post('likes/delete', this.like).then(()=>{
              this.$http.get('users/'+service.id+"/likes").then(response => {
                if(response.body != null) this.loggedUserLikes = response.body;
                else this.loggedUserLikes = [];
                this.togglingLike = false;
                this.getComments();
              });
            });
          });
        }
      }
    },
    deleteComment(e, comment){
      this.$http.post('comments/delete', comment);
      for(var i = 0 ; i < this.comments.length ; i++){
        if(this.comments[i].id == comment.id){
          this.comments.splice(i, 1);
        }
      }
      this.getComments();
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
      this.addingNewRating = true;
      if(this.articleRating == null)  this.loadingAverageRating = true;
      this.myRate.rating = rating;
      if(this.myRate.id == null){
        this.$http.post('rating/add', this.myRate).then(()=>{
          this.$http.get('articles/'+this.article.article.id+'/rating/user/'+service.id).then(response => {
            if(response.body != null){
              this.myRate.id = response.body[0].id;
            }
            else {
              this.myRate.id = null;
            }
            this.addingNewRating = false;
          });
          this.getAverageRating();
        });
      }
      else {
        this.$http.put('rating/update', this.myRate).then(()=>{
          this.addingNewRating = false;
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
    },
    toggleBibliography(){
      this.isBibliographyVisible = !this.isBibliographyVisible;
      if(this.isBibliographyVisible == true){
        this.showBibliographyText = "Ukryj";
      }
      else {
        this.showBibliographyText = "Bibliografia";
      }
    },
    goToUserInfo(id){
      this.$router.push({ name: 'user', params: { userId: id }});
    },
    goToEditing(){
      this.$router.push({ name: 'editarticle', params: { article: this.article.article }});
    },
  },
  mounted(){
    this.image = this.$route.params.image;
    this.$http.get('articles/'+this.$route.params.id).then(response => {
      this.article = response.body;
      this.myRate.articleId = this.article.article.id;
      this.getAverageRating();
      this.loadingArticle = false;
      if(this.image == ""){
        this.$http.get('users/'+this.article.article.userId+'/avatar').then(response => {
          this.image = response.body[0].avatar;
          this.loadingImage = false;
        });
      }
    });


    if(this.image != "") {
      this.loadingImage = false;
    }

    this.myRate.userId = service.id;
    this.$http.get('articles/'+this.$route.params.id+'/rating/user/'+service.id).then(response => {
      if(response.body != null){
        this.myRate.rating = parseInt(response.body[0].rating);
        this.myRate.id = response.body[0].id;
      }
      else {
        this.myRate.rating = 0;
        this.myRate.id = null;
      }
      this.loadingRating = false;
    });

    this.getComments();

    this.$http.get('users/'+service.id+"/likes").then(response => {
      if(response.body != null){
        this.loggedUserLikes = response.body;
      }
      else this.loggedUserLikes = [];

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
  animation-name: animationPop;
  animation-duration: 1s;
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
  text-align: left;
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
  margin: 0 !important;
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
  text-align: left;
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
  width: 45px !important;
  min-width: 45px !important;
  height: 30px !important;
  min-height: 30px !important;
  margin-right: 5px;
  display: flex;
  flex-direction: row;
  align-items: center;
  justify-content: center;
  float: right;
  background-color: white;
  border-radius: 30px;
  box-shadow: 4px 4px 2px #ebebeb;
  border: 1px solid #ebebeb;
}
.likeIconComments img {
  width: 20px;
}
.deleteComment {
  width: 20px;
  min-width: 20px;
  height: 20px;
  min-height: 20px;
  display: flex;
  justify-content: center;
  align-self: center;
  align-items: center;
  margin-left: 20px;
  border-radius: 20px;
  transition: 0.3s;
}
.deleteComment:hover {
  transform: rotate(180deg);
  
}
.deleteComment img {
  width: 10px;
}
.numberOfLikes {
  margin-top: 13px;
  font-size: 9pt;
  margin-left: 3px;
  color: #7a7a7a;
}
.comContent {
  display: flex;
  flex-direction: row;
  
}
.comments {
  width: 100%;
  float: left;
}
.nocomments {
  width: 100%;
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
  animation-name: animationPop;
  animation-duration: 1s;
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
  cursor: pointer;
}
.likeEnabled {
  opacity: 1 !important;
  cursor: pointer;
}
.rating {
  margin-right: 30px;
}
.avgRating {
  justify-content: center;
  margin-bottom: 10px;
}
.averageRating {
  animation-name: animationPop;
  animation-duration: 1s;
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
  text-align: center;
}
.podpis {
  text-align: right;
  font-style: italic;
  font-size: 10pt;
  padding-right: 20px;
}
.avatarWithTooltip {
  width: 30px;
  margin-bottom: 10px;
  height: 30px;
  float: left;
  margin-right: 20px;
}
.loadingImage {
  width: 200px;
  height: 200px;
  min-width: 50px;
  margin-right: 1rem;
}
.bibliography a {
  cursor: pointer;
  font-size: 9pt;
}
.bibliography p {
  font-size: 7pt;
  font-style: italic;
}
.bibliography {
  text-align: left;
}
.inCommentH6 {
  flex-grow: 5;
}
.comOrNotCom {
  display: flex;
  flex-direction: column;
  width: 50%;
}
img {
  cursor: pointer;
  transition: 0.3s;
  
}
img:hover {
  transform: scale(0.98);
}
.edit {
  cursor: pointer;
  font-size: 8pt;
  margin-right: 30px;
  margin-bottom: .5rem;
}
.topRight {
  display: flex;
  flex-direction: row;
}

.fade-enter-active, .fade-leave-active {
  transition: opacity 1s;
}
.fade-enter, .fade-leave-to /* .fade-leave-active below version 2.1.8 */ {
  opacity: 0;
}



.fadeDelay-enter-active {
  transition: opacity 1s;
  transition-delay: 1s;
}
.fadeDelay-leave-active {
  transition: opacity 1s;
}
.fadeDelay-enter, .fadeDelay-leave-to /* .fade-leave-active below version 2.1.8 */ {
  opacity: 0;
}


@keyframes animationPop {
  from {opacity: 0}
  to {opacity: 1}
}
#image {
  animation-name: animationPop;
  animation-duration: 1s;
}
#komentarze {
  animation-name: animationPop;
  animation-duration: 1s;
}
li {
  animation-name: animationPop;
  animation-duration: 1s;
}

</style>
