<template>
  <div id="articleMain">
    <div class="media">
      <img src="./assets/avatar.jpg" class="mr-3 avatar">
      <div class="media-body">
        <div class="header">
          <h2 class="mt-0">{{article.title}}</h2>
          <h6>{{article.date}}</h6>
        </div>
        <p>{{article.content}}</p>
        <div class="bottom">
          <button type="button" class="btn btn-primary like"><img class="likeIcon" src="./assets/likeWhite.svg" />Like</button>
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
    }
  },
  mounted(){
    this.$http.get('articles/'+this.$route.params.id).then(response => {
      this.article = response.body[0];
    });
  }
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
.comment {
  width: 30px;
  margin-right: 20px;
  padding: 5px;
  margin-left: 5px;
}
.bottom {
  display: flex;
  flex-direction: row;
  justify-content: flex-end;
  align-items: center;
}
</style>
