<template>
<div id="adminpanel">
  <ul class="list-group" :key="user.id" v-for="user in users">
    <li class="list-group-item">
      <span>{{user.id}}. <b>{{user.name}} {{user.surname}}</b>, <i>{{user.email}}</i></span>
      <button type="button" class="btn btn-outline-danger delete" @click="deleteUser(user)">Usuń</button>
    </li>
  </ul>

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
    deleteUser(user){
      this.$http.post('users/delete', user).then(()=>{
        this.getUsers();
      });
    }
  },
}
</script>

<style scoped>
#adminpanel {
  text-align: center;
  display: flex;
  flex-direction: column;
}
.list-group-item {
  width: 50%;
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
@keyframes animationPop {
  from {opacity: 0}
  to {opacity: 1}
}
ul {
  animation-name: animationPop;
  animation-duration: 1s;
}
</style>
