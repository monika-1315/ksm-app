import Vue from 'vue'
import VueRouter from 'vue-router'
import "bootstrap/dist/css/bootstrap.min.css";

import App from './components/App'
import Welcome from './components/Welcome'
import Page from './components/Page'
import KSMApp from './components/KSMApp'


import 'es6-promise/auto'
import axios from 'axios'

import VueAuth from '@websanova/vue-auth'
import VueAxios from 'vue-axios'
import auth from './auth'
import router from './router'



Vue.router = router
Vue.use(VueRouter)


// Set Vue authentication
Vue.use(VueAxios, axios)
Vue.axios.defaults.baseURL = 'http://localhost:8000/api/v1'

Vue.use(VueAuth, auth)
const app = new Vue({
    el: '#app',
    components: { 
      App,
      ksmapp: KSMApp,
      Welcome,
      Page,
    },
    router,
    axios
});

export default router

