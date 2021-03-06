import Dashboard from './components/Dashboard.vue';
import Home from './components/Home.vue';
import Register from './components/Register.vue';
import Login from './components/Login.vue';
import EditData from './components/EditData.vue';
import AuthorizeUsr from './components/AuthorizeUsr.vue';
import AddUser from './components/AddUser.vue'
import NewMessage from './components/NewMessage.vue'
import EditMessage from './components/EditMessage.vue'
import AuthorMessages from './components/AuthorMessages.vue'
import Divisions from './components/Divisions.vue'
import EditDivision from './components/EditDivision.vue'
import NewDivision from './components/NewDivision.vue'
import NewEvent from './components/NewEvent.vue'
import EditEvent from './components/EditEvent.vue'
import Events from './components/Events.vue'
import Event from './components/Event.vue'
import ControlPanel from './components/ControlPanel.vue'
import Calendar from './components/Calendar.vue'
import Chart from './components/Chart.vue'
import Contact from './components/Contact.vue'
import DelegateAuthority from './components/DelegateAuthority.vue'
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
            requiresAuth: false
        }
    },

    {
        path: '/login',
        name: 'login',
        component: Login,
        meta: {
            requiresAuth: false
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
            requiresAuth: true
        }
    },
    {
        path: '/editdata',
        name: 'edit',
        component: EditData,
        meta: { 
            requiresAuth: true
        }
    },
    {
        path: '/newmessage',
        name: 'message',
        component: NewMessage,
        meta: { 
            requiresAuth: true
        }
    },
    {
        path: '/editmessage/:id',
        name: 'editmessage',
        component: EditMessage,
        meta: { 
            requiresAuth: true
        }
    },
    {
        path: '/editmessage',
        name: 'editmessages',
        component: AuthorMessages,
        meta: { 
            requiresAuth: true
        }
    },
    {
        path: '/divisions',
        name: 'divisions',
        component: Divisions,
        meta: { 
            requiresAuth: true
        }
    },
    {
        path: '/divisions/:id',
        name: 'division',
        component: EditDivision,
        meta: { 
            requiresAuth: true
        }
    },
    {
        path: '/newdivision',
        name: 'newdivision',
        component: NewDivision,
        meta: { 
            requiresAuth: true
        }
    },
    {
        path: '/panel',
        name: 'panel',
        component: ControlPanel,
        meta: { 
            requiresAuth: true
        }
    },
    {
        path: '/calendar',
        name: 'calendar',
        component: Calendar,
        meta: {  
            requiresAuth: false
        }
    },
    {
        path: '/contact',
        name: 'contact',
        component: Contact,
        meta: { 
            requiresAuth: false
        }
    },
    {
        path: '/delegate',
        name: 'delegate',
        component: DelegateAuthority,
        meta: { 
            requiresAuth: true
        }
    },
    {
        path: '/statistics',
        name: 'chart',
        component: Chart,
        meta: { 
            requiresAuth: false
        }
    },
    {
        path: '/newevent',
        name: 'newevent',
        component: NewEvent,
        meta: { 
            requiresAuth: true
        }
    },
    {
        path: '/editevent/:id',
        name: 'editevent',
        component: EditEvent,
        meta: { 
            requiresAuth: true
        }
    },
    {
        path: '/event/:id',
        name: 'showevent',
        component: Event,
        meta: { 
            requiresAuth: true
        }
    },
    {
        path: '/events',
        name: 'events',
        component: Events,
        meta: { 
            // requiresAuth: true
        }
    },
    {
        path: '/*',
        redirect: { name: 'events' }
    }
    ]
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