import Vue from 'vue'
import VueRouter from 'vue-router'
import "bootstrap/dist/css/bootstrap.min.css";

Vue.use(VueRouter)

import App from './components/App'
import Welcome from './components/Welcome'
import Page from './components/Page'
import KSMApp from './components/KSMApp'

import store from './store/index';

function guard(to, from, next){
    if(!store.state.login.isLoggedIn === true) {
        // or however you store your logged in state
        next(); // allow to enter route
    } else{
        next('/login'); // go to '/login';
    }
}
const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/home',
            name: 'welcome',
            component: Welcome,
            props: { title: "This is the SPA home" }
        },
        {
            path: '/spa-page',
            name: 'page',
            component: Page,
            props: { 
                title: "This is the SPA Second Page",
                author : {
                    name : "Fisayo Afolayan",
                    role : "Software Engineer",
                    code : "Always keep it clean"
                }
            },
            beforeEnter: guard
        },  
        {
            path: '/ksm',
            name: 'KSM',
            component: KSMApp
        }  ,
        {
            path: '/login',
            name: 'Login',
            component: KSMApp
        }
    ],
})
const app = new Vue({
    el: '#app',
    components: { App },
    router,
});

