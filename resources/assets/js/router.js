import App from './App.vue';
import Dashboard from './components/Dashboard.vue';
import Home from './components/Home.vue';
import Register from './components/Register.vue';
import Login from './components/Login.vue';
import EditData from './components/EditData.vue';
import AuthorizeUsr from './components/AuthorizeUsr.vue';
import AddUser from './components/AddUser.vue'
import VueRouter from 'vue-router';
import store from './store';

const router = new VueRouter({
    mode: 'history',
    routes: [
    {
        path: '/',
        name: 'home',
        component: Home
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
        path: '/login',
        name: 'login',
        component: Login,
        meta: {
            auth: false
        }
    },

    {
        path: '/authorize',
        name: 'authorize',
        component: AuthorizeUsr,
        meta: {
            requiresAuth: true
        }
    },

    {
        path: '/dashboard',
        name: 'dashboard',
        component: Dashboard,
        meta: { requiresAuth: true }
    },
    {
        path: '/adduser',
        name: 'adduser',
        component: AddUser,
        meta: { 
            auth: true
        }
    },
    {
        path: '/editdata',
        name: 'edit',
        component: EditData,
        meta: { 
            auth: true
        }
    },]
});

router.beforeEach((to, from, next) => {

    // check if the route requires authentication and user is not logged in
    if (to.matched.some(route => route.meta.requiresAuth) && !store.state.isLoggedIn) {
        // redirect to login page
        next({ name: 'login' })
        return
    }

    // if logged in redirect to dashboard
    if(to.path === '/login' && store.state.isLoggedIn) {
        next({ name: 'dashboard' })
        return
    }

    next()
});

export default router;