<template>
<div id="profile">

  <div class="container">
    <div class="form">
      <h3>Twój profil</h3>
      <form>
        <div class="form-row">
          <div class="col">
            <input type="text" class="form-control" placeholder="Imię" v-model="user.name" disabled>
          </div>
          <div class="col">
            <input type="text" class="form-control" placeholder="Nazwisko" v-model="user.surname" disabled>
          </div>
        </div>
        <div class="form-group">
          <input type="email" class="form-control" placeholder="Email" v-model="user.email" required>
        </div>
        <div class="form-group">
          <textarea type="text" class="form-control" placeholder="Opis" aria-describedby="descriptionHelp" v-model="user.description"></textarea>
          <small id="descriptionHelp" class="form-text text-muted">Zaktualizuj swój opis</small>
        </div>

        <div class="form-group custom-file">
          <input type="file" class="custom-file-input" @change="onFileChange($event)" aria-describedby="avatarHelp">
          <small id="avatarHelp" class="form-text text-muted">Dodaj nieco świeżości i zaktualizuj swoje zdjęcie!</small>
          <label class="custom-file-label">Wybierz zdjęcie</label>
        </div>

        <div class="form-group">
          <input type="nick" class="form-control nickInput" placeholder="Nick" v-model="user.nick" aria-describedby="nickHelp" disabled>
            <small id="nickHelp" class="form-text text-muted">Ustalonego przy rejestracji nicku nie można już zmienić...</small>
        </div>
        <div class="form-group pass">
          <input type="password" class="form-control" placeholder="Bieżące hasło" v-model="user.password">
          <button class="btn btn-warning chPass" data-toggle="changePasswordDialog">Zmień hasło</button>
        </div>
        <div class="buttons">
          <button type="submit" class="btn btn-primary" @click="update()">Zaktualizuj dane</button>
        </div>
      </form>
    </div>
    <div>
      <img class="avatar" :src="user.avatar">
    </div>
  </div>

  <md-dialog-alert
      :md-active.sync="success"
      md-title="Udało się!"
      md-content="Pomyślnie zaktualizowano Twój profil!"
      md-confirm-text="Zamknij" />
  <md-dialog-alert
      :md-active.sync="failed"
      md-title="Coś poszło nie tak..."
      md-content="Sprawdź poprawność wszystkich pól."
      md-confirm-text="Zamknij" />




</div>
</template>

<script>
import service from './service.js';

export default {
  name: 'profile',
  components: {
  },
  data(){
    return {
      user: {
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
      changePasswordDialog: false,
    }
  },
  mounted(){
    this.user.name = service.name;
    this.user.surname = service.surname;
    this.user.nick = service.nick;
    this.user.description = service.description;
    this.user.email = service.email;
  },
  methods: {
    onFileChange(event) {
      var input = event.target;
      if (input.files && input.files[0]) {
          var reader = new FileReader();
          reader.onload = (e) => {
              this.user.avatar = e.target.result;
          }
          reader.readAsDataURL(input.files[0]);
      }
    },
    update(){
      if(this.user.email != "" && this.user.password != ""){
        this.$http.post('validateLogin', this.user).then(response => {
          if(response.body == "true"){
            this.$http.put('users/update', this.user);
            this.user.password = "";
            service.email = this.user.email;
            service.description = this.user.description;
            this.success = true;
          }
          else {
            this.user.password = "";
            this.failed = true;
          }
        });
      }
      else {
        this.user.password = "";
        this.failed = true;
      }
    },
    changePassword(){

    },
    goToLogin(){
      this.$router.replace({ name: "login" });
    }
  },
}
</script>

<style scoped>
#profile {
  text-align: center;
}
h3 {
  margin-bottom: 20px;
  text-align: center;
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
.pass {
  display: flex;
  flex-direction: row;
  justify-content: space-between;
}
.chPass {
  width: 170px;
  margin-left: 20px;
}
</style>
