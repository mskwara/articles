<template>
<div id="login">
  <h3>Zaloguj się</h3>
  <div class="container">
    <form>
      <div class="form-group">
        <input type="text" class="form-control" placeholder="Nick" v-model="input.nick" required>
      </div>

      <div class="form-group">
        <input type="password" class="form-control" placeholder="Hasło" v-model="input.password" required>
      </div>
      <div class="buttons">
        <button type="submit" class="btn btn-primary" @click="login()">Zaloguj</button>
        <a @click="goToRegister()">Nie masz konta?</a>
      </div>
    </form>

  </div>

  <md-dialog-alert
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
    }
  },
  mounted(){
    service.authenticated = false;
  },
  methods: {
    login() {
      if(this.input.nick != "" && this.input.password != "") {
        this.$http.post('validateLogin', this.input).then(response => {
          if(response.body == "true"){
              this.$emit("authenticated", true);

              this.$http.get('users/'+this.input.nick).then(response => {
                this.setLoggedUser(response.body[0].id, response.body[0].nick, response.body[0].name, response.body[0].surname, response.body[0].email, response.body[0].description);
              });
              this.$router.replace({ name: "home" });
          } else {
              this.failedText = "Wpisałeś niepoprawny nick lub hasło!";
              this.failed = true;
              this.input.nick = "";
              this.input.password = "";
          }
        });
      } else {
        this.failedText = "Uzupełnij wszystkie pola żeby się zalogować!";
        this.failed = true;
      }
    },
    goToRegister(){
      this.$router.replace({ name: "register" });
    },
    setLoggedUser(id, nick, name, surname, email, description){
      service.id = id;
      service.nick = nick;
      service.name = name;
      service.surname = surname;
      service.email = email;
      service.description = description;
    },
  },
}
</script>

<style scoped>
#login {
  padding-top: 50px;
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
</style>
