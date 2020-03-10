<template>
<div id="adminpanel">
  <div class="panel">
    <ul class="list-group" :key="user.id" v-for="user in users">
      <li class="list-group-item" v-if="user.id != 47 && user.nick != 'admin'">
        <span>{{user.id}}. <b>{{user.name}} {{user.surname}}</b>, <i>{{user.email}}</i></span>
        <button type="button" class="btn btn-outline-danger delete" @click="deletingUser(user)">Usuń</button>
      </li>
    </ul>
  </div>
  <div class="panel">
    <textarea class="form-control" rows="3" v-model="readyToCopy"></textarea>
    <button type="button" class="btn btn-outline-primary mailbtn" @click="getAllEmails()">Wszystkie emaile</button>
    <button type="button" class="btn btn-outline-primary" @click="getSleepingEmails()">Nieaktywni</button>
  </div>

  <md-dialog-confirm
      :md-active.sync="deleting"
      md-title="Jesteś pewien?"
      :md-content="deletingText"
      md-cancel-text="Anuluj"
      md-confirm-text="Zatwierdź"
      @md-confirm="deleteUser" />
</div>

</template>

<script>
import service from './service.js';

export default {
  name: 'adminpanel',
  components: {
  },
  data(){
    return {
      users: [],
      readyToCopy: "",
      userToDelete: [],
      deleting: false,
      deletingText: ""
    }
  },
  mounted(){
    if(service.id != 47 || service.nick != "admin") {
        this.$router.replace({ name: "login" });
    }
    this.getUsers();
  },
  methods: {
    getUsers(){
      this.$http.get('users').then(response => {
        this.users = response.body;
      });
    },
    deletingUser(user){
      this.userToDelete = user;
      this.deletingText = "Jeśli zatwierdzisz, usuniemy użytkownika "+this.userToDelete.name+" "+this.userToDelete.surname+".";
      this.deleting = true;
    },
    deleteUser(){   
      this.$http.post('users/delete', this.userToDelete).then(()=>{
        this.getUsers();
      });
    },
    getAllEmails(){
      this.$http.get('emails/all').then(response => {
        var array = response.body;
        var text = "";
        for(var i = 0 ; i < array.length ; i = i + 1){
          if(array[i].id != 47){
            text += array[i].email;
            text += ", ";
          }
        }
        this.readyToCopy = text;
      });
    },
    getSleepingEmails(){
      this.$http.get('emails/sleeping').then(response => {
        var array = response.body;
        var text = "";
        for(var i = 0 ; i < array.length ; i = i + 1){
          if(array[i].id != 47){
            text += array[i].email;
            text += ", ";
          }
        }
        this.readyToCopy = text;
      });
    }
  },
}
</script>

<style scoped>
#adminpanel {
  text-align: center;
  display: flex;
  flex-direction: row;
  justify-content: space-between;
}
.panel {
  display: flex;
  flex-direction: column;
  width: 48%
}
.list-group-item {
  width: 100%;
  text-align: left;
  display: flex;
  flex-direction: row;
  justify-content: space-between;
}
.list-group-item:hover {
  background-color: rgb(246, 255, 197);
}
span {
  display: flex;
  align-items: center;
  white-space: pre;
}
textarea {
  margin-bottom: 20px;
}
.mailbtn {
  margin-bottom: 20px;
}
@keyframes animationPop {
  from {opacity: 0}
  to {opacity: 1}
}
ul {
  animation-name: animationPop;
  animation-duration: 1s;
}
</style>
