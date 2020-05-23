import { mount, createLocalVue } from 'vue-test-utils';
import expect from 'expect';

import Register from "../components/Register.vue";
import Login from "../components/Login.vue";
import App from "../App.vue";
import Home from "../components/Home.vue";
import moxios from 'moxios'
import axios from 'axios';
import VueAxios from 'vue-axios'
// import VueRouter from 'vue-router';
// import router from '../router.js'
// import store from '../store.js'


const localVue = createLocalVue()
// localVue.use(VueRouter)
localVue.use(VueAxios, axios);

beforeEach(function () {
    moxios.install()
  })

  afterEach(function () {
    moxios.uninstall()
  })


describe('Login', () => {
    let wrapper = mount(Login, {
        localVue,
        axios
    });
    it.only('shows welcoming text', () => {
        expect(wrapper.html()).toContain("Witamy w aplikacji Katolickiego Stowarzyszenia Młodzieży Diecezji Legnickiej")
    })

    it('doesn\'t allow empty fields', done => {
        moxios.stubRequest('api/auth/login', {
            status: 200,
            response:{
                success: false,
                errors: {
                    message: ['wrong'],
                    email:['Pole email jest wymagane.'],
                    password:['Pole password jest wymagane.']
                }
            }
          })
        wrapper.find('button').trigger('click');
        
        wrapper.vm.$nextTick(() => {
            expect(wrapper.html()).toContain("Pole email jest wymagane.")
            expect(wrapper.html()).toContain("Pole password jest wymagane.")
            done()
          })
           
    })

    let type = (text, selector) => {

        let node = wrapper.find(selector);
        node.element.value = text;
        node.trigger('input');
    };
})