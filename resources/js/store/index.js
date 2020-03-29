import Vue from "vue"
import Vuex from "vuex"
import axios from 'axios'
import VueAxios from 'vue-axios'
//import login from './login';
Vue.use(Vuex, VueAxios, axios);
export default new Vuex.Store({
    namespaced: true,
    modules: {
        login
    },
    state: {
        windowHeight: 0,
        windowWidth: 0
    },
    actions: {
        // Shared actions
    },
    mutations: {
        ['setWindowHeight'](state, {windowHeight}) {
            state.windowHeight = windowHeight;
        },
        ['setWindowWidth'](state, {windowWidth}) {
            state.windowWidth = windowWidth;
        }
    },
    getters: {
        // Shared getters
    }
})