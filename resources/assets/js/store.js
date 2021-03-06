import Vue from 'vue'
import Vuex from 'vuex'
import axios from 'axios'

Vue.use(Vuex)

export default new Vuex.Store({
    state: {
        email: '',
        isLoggedIn: !!localStorage.getItem('token'),
        token: localStorage.getItem('token'),
        name: '',
        user_id: 0,
        division: 0,
        is_leadership: false,
        is_management: false,
        is_authorized: true,
        messages: null

    },
    mutations: {
        SaveMessages(state, messages){
            state.messages=messages;
        },
        LoginEmail(state, email) {
            state.email = email;
            axios.post('/api/auth/getUser',{token: state.token})
                .then(function (response) {
                    state.division = response.data[0].division;
                    state.user_id = response.data[0].id;
                    state.is_leadership = response.data[0].is_leadership;
                    state.is_management = response.data[0].is_management;
                    state.is_authorized = response.data[0].is_authorized;
                    state.name = response.data[0].name;
                }.bind(this));
        },
        LoginUser(state, data) {
            state.isLoggedIn = true;
            let token = data.access_token;
            state.token = token;
            localStorage.setItem('token', token)
        },
        LogoutUser(state) {
            state.isLoggedIn = false;
            state.data = '';
            state.token = localStorage.removeItem('token');
            state.email = '';
            state.division = 0;
            state.user_id = 0;
            state.is_leadership = false
            state.is_management = false
            state.is_authorized = true
            state.name = '';
            state.messages= null
        },
        tokenStored(state) {
            state.token = localStorage.getItem('token')
        },
        refreshUser(state, data) {

            state.division = data.division;
            state.is_leadership = data.is_leadership;
            state.is_management = data.is_management;
            state.is_authorized = data.is_authorized;
            state.name = data.name;
            state.user_id = data.id;
        }

    }
})