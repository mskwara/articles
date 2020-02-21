<template>
<div id="statistics">
  <div class="page">
    <h3>Twoje statystyki</h3>
    <stats-component :userId="userId" :current="true"></stats-component>
  </div>
</div>
</template>

<script>
import service from './service.js';
import StatsComponent from './StatsComponent.vue';

export default {
  name: 'statistics',
  components: {
    StatsComponent
  },
  data(){
    return {
      userId: service.id,
    }
  },
  mounted(){

  },
  methods: {

  },
}
</script>

<style scoped>
#statistics {
  text-align: center;
  display: flex;
  flex-direction: row;
  justify-content: center;
}

@keyframes animationPop {
  from {opacity: 0}
  to {opacity: 1}
}
.page {
  animation-name: animationPop;
  animation-duration: 1s;
}
</style>
