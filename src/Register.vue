<template>
<div id="register">
  <h3>Zarejestruj się</h3>
  <div class="container">
    <div class="form">

      <form>
        <div class="form-row">
          <div class="col">
            <input type="text" class="form-control" placeholder="Imię" v-model="newUser.name" required>
          </div>
          <div class="col">
            <input type="text" class="form-control" placeholder="Nazwisko" v-model="newUser.surname" required>
          </div>
        </div>
        <div class="form-group">
          <input type="email" class="form-control" placeholder="Email" v-model="newUser.email" required>
        </div>
        <div class="form-group">
          <textarea type="text" class="form-control" placeholder="Opis" aria-describedby="descriptionHelp" v-model="newUser.description"></textarea>
          <small id="descriptionHelp" class="form-text text-muted">Napisz coś o sobie</small>
        </div>

        <div class="form-group custom-file">
          <input type="file" class="custom-file-input" @change="onFileChange($event)" aria-describedby="avatarHelp">
          <small id="avatarHelp" class="form-text text-muted">Wraz ze zdjęciem twoje artykuły będą prezentować się znacznie lepiej!</small>
          <label class="custom-file-label">Wybierz zdjęcie</label>
        </div>

        <div class="form-group">
          <input type="nick" class="form-control nickInput" placeholder="Nick" v-model="newUser.nick" aria-describedby="nickHelp" required>
            <small id="nickHelp" class="form-text text-muted">Będzie Ci potrzeby do logowania</small>
        </div>
        <div class="form-group">
          <input type="password" class="form-control" placeholder="Hasło" v-model="newUser.password">
        </div>
        <div class="buttons">
          <button type="submit" class="btn btn-primary" @click="register()">Zarejestruj</button>
          <a @click="goToLogin()">Masz już konto?</a>
        </div>
      </form>
    </div>
    <div>
      <img v-if="newUser.avatar.length > 0" class="avatar" :src="newUser.avatar">
    </div>
  </div>

  <md-dialog-alert
      :md-active.sync="success"
      md-title="Udało się!"
      md-content="Pomyślnie stworzono nowe konto. Przejdź do panelu logowania."
      md-confirm-text="Zamknij" />
  <md-dialog-alert
      :md-active.sync="failed"
      md-title="Coś poszło nie tak..."
      md-content="Nie udało się zarejestrować. Sprawdź poprawność wszystkich pól."
      md-confirm-text="Zamknij" />

</div>
</template>

<script>

export default {
  name: 'register',
  components: {
  },
  data(){
    return {
      newUser: {
        name: "",
        surname: "",
        nick: "",
        description: "",
        email: "",
        password: "",
        avatar: "",
      },
      success: false,
      failed: false,
    }
  },
  mounted(){

  },
  methods: {
    onFileChange(event) {
      var input = event.target;
      if (input.files && input.files[0]) {
          var reader = new FileReader();
          reader.onload = (e) => {
              this.newUser.avatar = e.target.result;
          }
          reader.readAsDataURL(input.files[0]);
      }
    },
    register(){
      if(this.newUser.name != "" && this.newUser.surname != ""
       && this.newUser.nick != "" && this.newUser.email != "" && this.newUser.password != ""){
            this.$http.post('users/add', this.newUser);
            this.newUser.name = "";
            this.newUser.surname = "";
            this.newUser.nick = "";
            this.newUser.description = "";
            this.newUser.email = "";
            this.newUser.password = "";
            this.newUser.avatar = "";
            this.success = true;
          }
          else {
            this.failed = true;
            this.newUser.password = "";
          }
    },
    goToLogin(){
      this.$router.replace({ name: "login" });
    }
  },
}
</script>

<style scoped>
#register {
  text-align: center;
  padding-top: 50px;
}
h3 {
  margin-bottom: 20px;
}
.form-row {
  margin-bottom: 15px;
}
.custom-file {
  margin-bottom: 15px;
}
.custom-file-label {
  color: #737373 !important;
}
.container {
  display: flex;
  flex-direction: row;
  justify-content: center;
  width: 100%;
  margin-top: 30px;
}
.form {
  width: 50%;
  text-align: left;
}
.avatar {
  max-width: 300px;
  max-height: 300px;
  width: 300px;
  height: 300px;
  margin-left: 40px;
  margin-top: 60px;
  border-radius: 300px;
}
.buttons {
  width: 100%;
  position: relative;
  text-align: center;
}
a {
  position: absolute;
  right: 0;
  cursor: pointer;
}
.nickInput {
  margin-top: 20px;
}
</style>
