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
            <h6>{{article.date}}</h6>
          </div>
          <p>{{article.content}}</p>
          <div class="bottom">
            <button type="button" class="btn btn-primary like" @click="like()"><img class="likeIcon" src="./assets/likeWhite.svg" />Like</button>
          </div>
        </div>
      </div>
      <h4>Comments</h4>
      <div class="commentSection">
        <div class="comments" v-if="comments != null">

          <ul class="list-group">
            <li class="list-group-item" :key="comment.id" v-for="comment in comments">
              <img src="./assets/avatar.jpg" class="mr-3 commentAvatar">
              <div class="comContent">
                <h6>
                  <a class="username">Michał Skwara</a>
                  {{comment.content}}
                </h6>
                <div class="likeIconComments">
                  <img src="./assets/like.svg" @click="likeComment(comment)" />
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
      },
      getCommentsInterval: null,
      loading: true,
      loadingNewComment: false,
    }
  },
  methods: {
    addComment(){
      this.loadingNewComment = true;
      this.newComment.articleId = this.$route.params.id;
      //this.newComment.userId = ;
      this.$http.post('comments/add', this.newComment);
      this.newComment.content = "";
      this.getComments();
      this.$http.put('articles/'+this.$route.params.id+'/commentsNumber');
    },
    getComments(){
      this.$http.get('articles/'+this.$route.params.id+'/comments').then(response => {
        this.comments = response.body;
        this.loading = false;
        this.loadingNewComment = false;
      });
    },
    like(){
      this.$http.put('articles/'+this.$route.params.id+'/likes');
    },
    likeComment(comment){
      this.$http.put('comments/'+comment.id+'/likes');
      this.getComments();
    }
  },
  mounted(){
    this.$http.get('articles/'+this.$route.params.id).then(response => {
      this.article = response.body[0];
    });
    this.getComments();
    this.getCommentsInterval = setInterval(this.getComments, 5000);
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
</style>
