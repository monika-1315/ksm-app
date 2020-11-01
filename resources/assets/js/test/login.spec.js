import { mount, createLocalVue } from 'vue-test-utils';
import expect from 'expect';

import Login from "../components/Login.vue";
import moxios from 'moxios'
import axios from 'axios';
import VueAxios from 'vue-axios'
import flushPromises from 'flush-promises'
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
  it('shows welcoming text', () => {
    expect(wrapper.html()).toContain("Witamy w aplikacji Katolickiego Stowarzyszenia Młodzieży Diecezji Legnickiej")
  })
  it('links the KSM website', () => {
    expect(wrapper.html()).toContain('href="http://ksm.legnica.pl"')
  })
  
  it('has working data binding', () => {
    expect(wrapper.vm.email).toBe('');
    type('monika@m.pl', 'input[id=email]');
    expect(wrapper.vm.email).toBe('monika@m.pl');

    expect(wrapper.vm.password).toBe('');
    type('Marusiak', 'input[id=password]');
    expect(wrapper.vm.password).toBe('Marusiak');
  })

  it('shows progress bar after clicking button', (done) => {

    wrapper.find('#log-in').trigger('click');
    moxios.wait(function () {
      let request = moxios.requests.mostRecent()
      request.respondWith({
        status: 200,
        response: {
          success: false,
          errors: {
            message: ['Błędny email lub hasło'],
            email: ['Pole email jest wymagane.'],
            password: ['Pole password jest wymagane.']
          }
        }
      }).then(function () {
        expect(wrapper.html()).toContain('progress');
        done()
      })

    })
  })

  let type = (text, selector) => {

    let node = wrapper.find(selector);
    node.element.value = text;
    node.trigger('input');
  };
})