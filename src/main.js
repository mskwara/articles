import Vue from 'vue'
import App from './App.vue'
import Articles from './Articles.vue'
import ArticleMain from './ArticleMain.vue'
import 'bootstrap/dist/css/bootstrap.min.css';
import VueRouter from 'vue-router'
import VueResource from "vue-resource";
import VueMaterial from 'vue-material'
import 'vue-material/dist/vue-material.min.css'
import 'vue-material/dist/theme/default.css'

Vue.use(VueMaterial)


Vue.use(VueResource);

Vue.use(VueRouter)

Vue.config.productionTip = false
Vue.http.options.root = '/api';

const router = new VueRouter({
  routes: [
    { path: '/', component: Articles },
    { path: '/article/:id', component: ArticleMain },
  ],
  scrollBehavior() {
    document.getElementById('app').scrollIntoView();
  }
})

new Vue({
  render: h => h(App),
  router
}).$mount('#app')
