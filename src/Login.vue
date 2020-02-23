<template>
<div id="login">
  <h3>Zaloguj się</h3>
  <div class="container">
    <form v-on:submit.prevent="login()">
      <div class="form-group">
        <input type="text" class="form-control" placeholder="Nick" v-model="input.nick" required>
      </div>

      <div class="form-group">
        <input type="password" class="form-control" placeholder="Hasło" v-model="input.password" required>
      </div>
      <div class="buttons">
        <button type="submit" class="btn btn-success">Zaloguj</button>
        <a @click="goToRegister()">Nie masz konta?</a>
      </div>
    </form>
    
    <div class="spinner-border text-primary loading" role="status" v-if="loading">
      <span class="sr-only">Loading...</span>
    </div>
    <div class="warning" v-if="screenSizeAlert">
      <h4>Uwaga!</h4>
      <p>Ze względu na niekompatybilny rozmiar ekranu treści na stronie mogą nie wyświetlać się prawidłowo.</p>
    </div>

  </div>

  <md-dialog-alert class="error"
      :md-active.sync="failed"
      md-title="Coś poszło nie tak..."
      :md-content="failedText"
      md-confirm-text="Zamknij" />

</div>
</template>

<script>
import service from "./service.js";

export default {
  name: 'login',
  components: {
  },
  data(){
    return {
      input: {
          nick: "",
          password: ""
      },
      failed: false,
      failedText: "",
      loading: false,
      windowWidth: 0,
      windowHeight: 0,
      screenSizeAlert: false,
    }
  },
  mounted(){
    service.authenticated = false;
    this.windowWidth = window.innerWidth;
    this.windowHeight = window.innerHeight;
    if(this.windowWidth < 1265){
      this.screenSizeAlert = true;
    }
  },
  methods: {
    login() {
      this.loading = true;
      if(this.input.nick != "" && this.input.password != "") {
        this.$http.post('validateLogin', this.input).then(response => {
          if(response.body == "true"){
              this.$emit("authenticated", true);
              service.authenticated = true;
              
              this.$http.get('users/'+this.input.nick).then(response => {
                this.setLoggedUser(response.body[0].id, response.body[0].nick, response.body[0].name,
                   response.body[0].surname, response.body[0].email, response.body[0].description, response.body[0].avatar);
                this.loading = false;
                this.$router.replace({ name: "home" }).catch(() => {});
              });
          } else {
              this.failedText = "Wpisałeś niepoprawny nick lub hasło!";
              this.failed = true;
              this.input.nick = "";
              this.input.password = "";
              this.loading = false;
          }
        }).catch(error => {
          alert(error);
        });
      } else {
       this.failedText = "Uzupełnij wszystkie pola żeby się zalogować!";
        this.failed = true;
       this.loading = false;
      }
    },
    goToRegister(){
      this.$router.replace({ name: "register" });
    },
    setLoggedUser(id, nick, name, surname, email, description, avatar){
      service.id = id;
      service.nick = nick;
      service.name = name;
      service.surname = surname;
      service.email = email;
      service.description = description;
      service.avatar = avatar;
    },
  },
}
</script>

<style scoped>
#login {
  padding-top: 20px;
}
h3 {
  margin-bottom: 20px;
}
.container {
  display: flex;
  flex-direction: column;
  align-items: center;
  width: 100%;
}
form {
  width: 50%;
}
input {
  min-width: 300px;
}
.buttons {
  width: 100%;
  position: relative;
}
a {
  position: absolute;
  right: 0;
  cursor: pointer;
}
.loading {
  margin-top: 20px;
}
.error {
  border: 2px solid red !important;
  border-radius: 10px;
}
.warning {
  border: 1px solid gray;
  border-radius: 3px;
  background-color: rgb(255, 255, 212);
  width: 400px;
  height: auto;
  padding: 10px;
  margin-top: 30px;
}
</style>
