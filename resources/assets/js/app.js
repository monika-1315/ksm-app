import Vue from 'vue';
import VueRouter from 'vue-router';
import axios from 'axios';
import VueAxios from 'vue-axios';
import App from './App.vue';
import store from './store';
import InfiniteScroll from 'v-infinite-scroll'
import 'v-infinite-scroll/dist/v-infinite-scroll.css';
import "bootstrap/dist/css/bootstrap.min.css";

import VueAuthenticate from 'vue-authenticate'
import router from './router'

Vue.use(InfiniteScroll);
Vue.use(VueRouter);
// Vue.use(axios);
Vue.use(VueAxios, axios);
Vue.use(VueAuthenticate, {
    baseUrl: 'http://lara-vue-auth.test', // Your API domain

    providers: {
        google: {
            clientId: '519675463204-7bc7lvaqti1teo41im6e5he5lbjjvthk.apps.googleusercontent.com',
            redirectUri: 'http://localhost:8000/' // Your client app URL
        }
    }
});

//vue-toaster
import Toaster from 'v-toaster';
import 'v-toaster/dist/v-toaster.css';

Vue.use(Toaster, {timeout: 2000});

Vue.router = router
// Vue.use(require('@websanova/vue-auth'), {
//     auth: require('@websanova/vue-auth/drivers/auth/bearer.js'),
//     http: require('@websanova/vue-auth/drivers/http/axios.1.x.js'),
//     router: require('@websanova/vue-auth/drivers/router/vue-router.2.x.js'),
// });
App.router = Vue.router
// new Vue(App).$mount('#app');
new Vue({
    store,
    
    el: '#app',
    axios,
    render: h => h(App)
});