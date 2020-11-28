import Vue from 'vue';
import VueRouter from 'vue-router';
import axios from 'axios';
import VueAxios from 'vue-axios';
import App from './App.vue';
import store from './store';
import InfiniteScroll from 'v-infinite-scroll'
import 'v-infinite-scroll/dist/v-infinite-scroll.css';
import "materialize-css/dist/css/materialize.min.css";
import "materialize-css/dist/js/materialize.min.js";
import "bootstrap/dist/css/bootstrap.min.css";
import Toaster from 'v-toaster';
import 'v-toaster/dist/v-toaster.css';
import router from './router'

Vue.use(InfiniteScroll);
Vue.use(VueRouter);
Vue.use(VueAxios, axios);

Vue.use(Toaster, {timeout: 2000});

Vue.router = router

App.router = Vue.router
//initialize Vue app instance
new Vue({
    store,
    
    el: '#app',
    axios,
    render: h => h(App)
});