require('./bootstrap');
import "bootstrap/dist/css/bootstrap.min.css";

window.Vue = require('vue');
import 'es6-promise/auto'
import axios from 'axios'
import Vue from 'vue'
import VueAuth from '@websanova/vue-auth'
import VueAxios from 'vue-axios'
import VueRouter from 'vue-router'
import auth from './auth'
import router from './vueapp'
//import App from './components/App.vue';
import Welcome from './components/Welcome.vue';
import Page from './components/Page.vue';
import KSMApp from './components/KSMApp.vue';
import Index from './Index.vue';


// const app = new Vue({
//   el: '#app',
//   components: {
//     ksmapp: KSMApp,
//     Welcome,
//     Page,
//     Index
//   }
// });

window.Vue = Vue

// Set Vue router
Vue.router = router
Vue.use(VueRouter)

// Set Vue authentication
Vue.use(VueAxios, axios)
axios.defaults.baseURL = `${process.env.MIX_APP_URL}/api/v1`

Vue.use(VueAuth, auth)

// Load Index
Vue.component('index', Index)
const app = new Vue({
  el: '#app',
  components :{
  router,
  index: Index
  }
});