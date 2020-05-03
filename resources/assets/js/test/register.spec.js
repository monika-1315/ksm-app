import { mount, createLocalVue } from 'vue-test-utils';
import expect from 'expect';

import Register from "../components/Register.vue";
// import Login from "../components/Login.vue";
import App from "../App.vue";
import Home from "../components/Home.vue";

import axios from 'axios';
import VueAxios from 'vue-axios'
// import VueRouter from 'vue-router';
// import router from '../router.js'
// import store from '../store.js'


const localVue = createLocalVue()
// localVue.use(VueRouter)
localVue.use(VueAxios, axios);

describe('Home', () => {
    let wrapper = mount(Home);
    it('shows welcoming text', () => { 
        expect(wrapper.html()).toContain("Witamy w aplikacji Katolickiego Stowarzyszenia Młodzieży Diecezji Legnickiej")
    }) 

    // 
}) 

describe('Register', () => {
    let wrapper = mount(Register, {
        localVue,
        axios
    });
    it('shows register text', () => { 
        expect(wrapper.html()).toContain("rejestruj")
    }) 
    it('starts with no division selected', ()=>{
            expect(wrapper.vm.division).toBe(0);
        })
        it('starts with no error', ()=>{
            expect(wrapper.vm.error).toBe(false);
            expect(wrapper.vm.errors.length).toBe(undefined);
        })
})