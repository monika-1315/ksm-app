import Vue from 'vue'
import VueRouter from 'vue-router'
import "bootstrap/dist/css/bootstrap.min.css";

Vue.use(VueRouter)

import App from './components/App'
import Welcome from './components/Welcome'
import Page from './components/Page'
import KSMApp from './components/KSMApp'
import Home from './pages/Home'
// import About from './pages/About'
import Register from './pages/Register'
import Login from './pages/Login'
import Dashboard from './pages/Dashboard'
// import Dashboard from './pages/user/Dashboard'
// import AdminDashboard from './pages/admin/Dashboard'
import Index from './Index.vue';


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
            // beforeEnter: guard
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
        },
        {
            path: '/h',
            name: 'home',
            component: Home,
            meta: {
              auth: undefined
            }
          },
        
          {
            path: '/register',
            name: 'register',
            component: Register,
            meta: {
              auth: false
            }
          },
          {
            path: '/login2',
            name: 'login',
            component: Login,
            meta: {
              auth: false
            }
          },
          // USER ROUTES
          {
            path: '/dashboard',
            name: 'dashboard',
            component: Dashboard,
            meta: {
              auth: true
            }
          },
    ],
})
const app = new Vue({
    el: '#app',
    components: { App,
    router,
    ksmapp: KSMApp,
    Welcome,
    Page,
    index:Index,
    render: h => h(Index)
    }
});

export default router

