import Vue from 'vue'
import Vuex from 'vuex'
import axios from 'axios'

Vue.use(Vuex)

export default new Vuex.Store({
    state: {
        email: localStorage.getItem('email'),
        isLoggedIn: !!localStorage.getItem('token'),
        token: localStorage.getItem('token'),
        name: '',
        division: 0,
        is_leadership: false,
        is_management: false,
        is_authorized: false
        
    },
    mutations: {
        LoginEmail (state, email) {
            localStorage.setItem('email', email);
            state.email=email;
            axios.post('/api/auth/getUser?token=' + state.token+'&email='+state.email)
                    .then(function (response) {
                    state.division = response.data[0].division;
                    state.is_leadership = response.data[0].is_leadership;
                    state.is_management = response.data[0].is_management;
                    state.is_authorized = response.data[0].is_authorized;
                    state.name = response.data[0].name;
                }.bind(this));
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
            state.division=0;
            state.is_leadership = false
            state.is_management = false
            state.is_authorized = false
            state.name = '';
        },
        tokenStored (state) {
            state.token = localStorage.getItem('token')
        },
        refreshUser(state){
            if (state.isLoggedIn && state.division===0){
                axios.post('/api/auth/getUser?token=' + state.token+'&email='+state.email)
                    .then(function (response) {
                    state.division = response.data[0].division;
                    state.is_leadership = response.data[0].is_leadership;
                    state.is_management = response.data[0].is_management;
                    state.is_authorized = response.data[0].is_authorized;
                    state.name = response.data[0].name;
                }.bind(this));
            }
        }
       
    }
})