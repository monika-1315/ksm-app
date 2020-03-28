import Vue from 'vue'
import VueRouter from 'vue-router'
import "bootstrap/dist/css/bootstrap.min.css";

Vue.use(VueRouter)

import App from './components/App'
import Welcome from './components/Welcome'
import Page from './components/Page'
import KSMApp from './components/KSMApp'

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
            }
        },  
        {
            path: '/ksm',
            name: 'KSM',
            component: KSMApp
        }  
    ],
})
const app = new Vue({
    el: '#app',
    components: { App },
    router,
});