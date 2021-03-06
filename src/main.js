import Vue from 'vue'
import App from './App.vue'
import Home from './Home.vue'
import Articles from './Articles.vue'
import ArticlesSearched from './ArticlesSearched.vue'
import ArticleMain from './ArticleMain.vue'
import Writing from './Writing.vue'
import Register from './Register.vue'
import Profile from './Profile.vue'
import User from './User.vue'
import Statistics from './Statistics.vue'
import EditArticle from './EditArticle.vue'
import AdminPanel from './AdminPanel.vue'
import Login from './Login.vue'
import 'bootstrap/dist/css/bootstrap.min.css';
import VueRouter from 'vue-router'
import VueResource from "vue-resource";
import VueMaterial from 'vue-material'
import 'vue-material/dist/vue-material.min.css'
import 'vue-material/dist/theme/default.css'

import StarRating from 'vue-star-rating'
import infiniteScroll from 'vue-infinite-scroll'
Vue.use(infiniteScroll)



Vue.component('star-rating', StarRating);

Vue.use(VueMaterial)


Vue.use(VueResource);

Vue.use(VueRouter)

Vue.config.productionTip = false
Vue.http.options.root = '/api';

const router = new VueRouter({
  routes: [
    { path: '/', name: "app", component: App },
    { path: '/home', name: 'home', component: Home,
      children: [
        { path: '/articles', name: 'articles', component: Articles, params: true },
        { path: '/articles/search/:phrase', name: 'articlessearched', component: ArticlesSearched, params: true},
        { path: '/article/:id', name: 'article', component: ArticleMain, params: true },
        { path: '/writing', name: 'writing', component: Writing },
        { path: '/profile', name: 'profile', component: Profile },
        { path: '/user', name: 'user', component: User, params: true },
        { path: '/statistics', name: 'statistics', component: Statistics },
        { path: '/editarticle', name: 'editarticle', component: EditArticle },
        { path: '/adminpanel', name: 'adminpanel', component: AdminPanel },
    ]},
    { path: '/login', name: 'login', component: Login },
    { path: '/register', name: 'register', component: Register },
  ],
  scrollBehavior() {
    document.getElementById('app').scrollIntoView();
  }
})

new Vue({
  render: h => h(App),
  router
}).$mount('#app')
