import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({
    state: {
        email: localStorage.getItem('email'),
        isLoggedIn: !!localStorage.getItem('token'),
        token: localStorage.getItem('token'),
        
    },
    mutations: {
        LoginEmail (state, email) {
            localStorage.setItem('email', email);
            state.email=email
        },
        LoginUser (state, data) {
            state.isLoggedIn = true;
            let token = data.access_token;
            state.token = token;
            localStorage.setItem('token', token)
        },
        LogoutUser (state) {
            state.isLoggedIn = false;
            state.data = '';
            state.token = localStorage.removeItem('token');
            state.email = localStorage.removeItem('email');
        },
        tokenStored (state) {
            state.token = localStorage.getItem('token')
        }
    }
})