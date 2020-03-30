import Register from './pages/Register'
import Login from './pages/Login'
import Dashboard from './pages/Dashboard'
import Home from './pages/Home'
import App from './components/App'
import Welcome from './components/Welcome'
import Page from './components/Page'
import KSMApp from './components/KSMApp'
import VueRouter from 'vue-router'
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
            path: '/',
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
          {
            path: '/dashboard2',
            name: 'dashboard',
            component: Dashboard,
            meta: {
              auth: false
            }
          },
    ],
})

export default router