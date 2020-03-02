<template>
<div id="profile">

  <div class="container">
    <div class="form">
      <h3>Twój profil</h3>
      <form v-on:submit.prevent="applying = true">
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
          <textarea type="text" class="form-control" placeholder="Opis" rows="5" aria-describedby="descriptionHelp" v-model="user.description"></textarea>
          <small id="descriptionHelp" class="form-text text-muted">Zaktualizuj swój opis</small>
        </div>

        <div class="form-group custom-file">
          <input type="file" class="custom-file-input" @change="onFileChange($event)" aria-describedby="avatarHelp">
          <small id="avatarHelp" class="form-text text-muted">
            Dodaj nieco świeżości i zaktualizuj swoje zdjęcie!<br>
            - Zdjęcie musi być kwadratowe o rozmiarze 200x200 px<br>
            - Maksymalny rozmiar to 200KB
            <a href="/photo_upload_instruction.pdf" download>Nie wiesz jak przyciąć i zmniejszyć zdjęcie?</a>
          </small>
          <label class="custom-file-label">Wybierz zdjęcie</label>
        </div>

        <div class="form-group">
          <input type="nick" class="form-control nickInput" placeholder="Nick" v-model="user.nick" aria-describedby="nickHelp" disabled>
            <small id="nickHelp" class="form-text text-muted">Ustalonego przy rejestracji nicku nie można już zmienić...</small>
        </div>
        <div class="form-group pass">
          <input type="password" class="form-control" placeholder="Nowe hasło" v-model="user.newPassword">
        </div>
        <div class="buttons">
          <button type="submit" class="btn btn-success">Zaktualizuj dane</button>
        </div>
      </form>
    </div>
    <div>
      <transition name="fade">
      <img v-if="user.avatar.length > 0" class="avatar" :src="user.avatar">
      </transition>
    </div>
  </div>

  <md-dialog-prompt
      :md-active.sync="applying"
      v-model="user.password"
      md-title="Podaj hasło by zatwierdzić zmiany"
      md-input-maxlength="30"
      md-input-placeholder="Hasło"
      md-confirm-text="Zatwierdź"
      @md-confirm="update" />

  <md-dialog-alert class="success"
      :md-active.sync="success"
      md-title="Udało się!"
      md-content="Pomyślnie zaktualizowano Twój profil!"
      md-confirm-text="Zamknij" />
  <md-dialog-alert class="error"
      :md-active.sync="failed"
      md-title="Coś poszło nie tak..."
      md-content="Sprawdź poprawność wszystkich pól."
      md-confirm-text="Zamknij" />

  <md-dialog-alert class="error"
      :md-active.sync="imageError"
      md-title="Mamy problem..."
      :md-content="imageErrorText"
      md-confirm-text="Zamknij" />




</div>
</template>

<script>
import service from './service.js';
import { EventBus } from './event-bus.js';

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
        newPassword: "",
        avatar: "",
      },
      success: false,
      failed: false,
      changePasswordDialog: false,
      applying: false,
      imageError: false,
    }
  },
  mounted(){
    this.user.name = service.name;
    this.user.surname = service.surname;
    this.user.nick = service.nick;
    this.user.description = service.description;
    this.user.email = service.email;
    this.user.avatar = service.avatar;
  },
  methods: {
    onFileChange(event) {
      const file = event.target.files[0];
      if(!file || file.type.indexOf('image/') !== 0){ //zły format pliku
        event.preventDefault();
        this.imageErrorText = "Wybrałeś nieprawidłowy format pliku!";
        this.imageError = true;
        return;
      }

      let reader = new FileReader();
      reader.readAsDataURL(file);
      reader.onload = evt => {
        let img = new Image();
        img.onload = () => {
          if(img.width != 200 || img.height != 200){    //czy ma wymiary 200x200
            event.preventDefault();
            this.imageErrorText = "Avatar musi mieć wymiary 200x200 px!";
            this.imageError = true;
            return;
          }
        }
        img.src = evt.target.result;
      }


      if (file.size > 1024 * 200) {    //czy rozmiar < 200 KB
        event.preventDefault();
        this.imageErrorText = "Plik ma zbyt duży rozmiar! Maksymalny dopuszczalny to 200KB.";
        this.imageError = true;
        return;
      }

      var input = event.target;
      if (input.files && input.files[0]) {
          reader = new FileReader();
          reader.onload = (e) => {
              this.user.avatar = e.target.result;
          }
          reader.readAsDataURL(input.files[0]);
      }

    },
    update(){
      if(this.user.email != "" && this.password != ""){
        //this.$http.post('validateLogin', this.user).then(response => {
          //if(response.body == "true"){
            this.$http.put('users/update', this.user).then(response => {
              if(response.body == "true"){
                this.user.password = "";
                service.email = this.user.email;
                service.description = this.user.description;
                service.avatar = this.user.avatar;
                this.user.password = "";
                this.success = true;
                EventBus.$emit('update-user');
              }
              else {
                this.user.password = "";
                this.failed = true;
              }
          //}
          //else {
          //  this.user.password = "";
          //  this.failed = true;
          //}
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
  animation-name: animationPop;
  animation-duration: 1s;
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
  width: 60%;
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
.error {
  border: 2px solid red !important;
  border-radius: 10px;
}
.success {
  border: 2px solid green !important;
  border-radius: 10px;
}

@keyframes animationPop {
  from {opacity: 0}
  to {opacity: 1}
}
</style>
