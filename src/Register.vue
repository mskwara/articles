<template>
<div id="register">
  <h3>Zarejestruj się</h3>
  <div class="container">
    <div class="form">

      <form v-on:submit.prevent="register()">
        <div class="form-row">
          <div class="col">
            <input type="text" class="form-control" placeholder="Imię" v-model="newUser.name">
          </div>
          <div class="col">
            <input type="text" class="form-control" placeholder="Nazwisko" v-model="newUser.surname">
          </div>
        </div>
        <div class="form-group">
          <input type="email" class="form-control" placeholder="Email" v-model="newUser.email">
        </div>
        <div class="form-group">
          <textarea type="text" class="form-control" placeholder="Opis" rows="5" aria-describedby="descriptionHelp" v-model="newUser.description"></textarea>
          <small id="descriptionHelp" class="form-text text-muted">Napisz coś o sobie</small>
        </div>

        <div class="form-group custom-file">
          <input type="file" class="custom-file-input" @change="onFileChange($event)" aria-describedby="avatarHelp">
          <small id="avatarHelp" class="form-text text-muted">
            Wraz ze zdjęciem twoje artykuły będą prezentować się znacznie lepiej!<br>
            - Zdjęcie musi być kwadratowe o rozmiarze 200x200 px<br>
            - Maksymalny rozmiar to 200KB
            <a href="/photo_upload_instruction.pdf" download>Nie wiesz jak przyciąć i zmniejszyć zdjęcie?</a>
          </small>
          <label class="custom-file-label">Wybierz zdjęcie</label>
        </div>

        <div class="form-group">
          <input type="nick" class="form-control nickInput" placeholder="Nick" v-model="newUser.nick" aria-describedby="nickHelp">
            <small id="nickHelp" class="form-text text-muted">
              Będzie Ci potrzeby do logowania<br>
              - Nie powinien zawierać znaków specjalnych
            </small>
        </div>
        <div class="form-group">
          <input type="password" class="form-control" placeholder="Hasło" v-model="newUser.password">
        </div>
        <div class="buttons">
          <button type="submit" class="btn btn-success">Zarejestruj</button>
          <a @click="goToLogin()">Masz już konto?</a>
        </div>
      </form>
    </div>
    <div>
      <img v-if="newUser.avatar.length > 0" class="avatar" :src="newUser.avatar">
    </div>
  </div>

  <md-dialog-alert class="success"
      :md-active.sync="success"
      md-title="Udało się!"
      md-content="Pomyślnie stworzono nowe konto. Przejdź do panelu logowania."
      md-confirm-text="Zamknij" />
  <md-dialog-alert class="error"
      :md-active.sync="failed"
      md-title="Coś poszło nie tak..."
      md-content="Nie udało się zarejestrować. Sprawdź poprawność wszystkich pól."
      md-confirm-text="Zamknij" />
  <md-dialog-alert class="error"
      :md-active.sync="notUniqueNick"
      md-title="Coś poszło nie tak..."
      md-content="Ten nick jest już używany."
      md-confirm-text="Zamknij" />
  <md-dialog-alert class="error"
      :md-active.sync="imageError"
      md-title="Mamy problem..."
      :md-content="imageErrorText"
      md-confirm-text="Zamknij" />
  <md-dialog-alert class="error"
      :md-active.sync="tooShortNick"
      md-title="Coś poszło nie tak..."
      md-content="Twój nick jest za krótki! Minimalna długość to 4 znaki."
      md-confirm-text="Zamknij" />
  <md-dialog-alert class="error"
      :md-active.sync="tooShortPassword"
      md-title="Coś poszło nie tak..."
      md-content="Twoje hasło jest za krótkie! Minimalna długość to 6 znaków."
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
      notUniqueNick: false,
      imageError: false,
      tooShortNick: false,
      tooShortPassword: false,
      imageErrorText: "",
    }
  },
  mounted(){

  },
  methods: {
    onFileChange(event) {
      //this.newUser.avatar = event.target.files[0];  //do firebase
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
              this.newUser.avatar = e.target.result;
          }
          reader.readAsDataURL(input.files[0]);
      }

    },
    register(){
      // const axios = require('axios').default;
      // const formData = new FormData()
      // formData.append('image', this.newUser.avatar, this.newUser.avatar.name)
      // axios.post('https://us-central1-oczymmyslisz-ad918.cloudfunctions.net/uploadFile ', formData)
      var specialChars = ["'", "\"", "/", "`", "[", "]", "{", "}", "\\", ":", "-"];
      for(var i = 0 ; i < specialChars.length ; i++){
        if(this.newUser.nick.includes(specialChars[i])){
          this.failed = true;
          return;
        }
      }

      if(this.newUser.name != "" && this.newUser.surname != ""
       && this.newUser.nick != "" && this.newUser.email != "" && this.newUser.password != ""){

          if(this.newUser.nick.length <= 3) {
            this.tooShortNick = true;
            return;
          }
          if(this.newUser.password.length <= 5) {
            this.tooShortPassword = true;
            return;
          }

            this.$http.post('validateUniqueNick', this.newUser).then(response => {
              if(response.body == "true"){
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
                this.notUniqueNick = true;
                this.newUser.nick = "";
                this.newUser.password = "";
              }
            });
          }
          else {
            this.failed = true;
            this.newUser.password = "";
          }

    },
    goToLogin(){
      this.$router.replace({ name: "login" });
    },

  },
}
</script>

<style scoped>
#register {
  text-align: center;
  padding-top: 20px;
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
.error {
  border: 2px solid red !important;
  border-radius: 10px;
}
.success {
  border: 2px solid green !important;
  border-radius: 10px;
}
</style>
