import { mount, createLocalVue } from 'vue-test-utils';
import expect from 'expect';

import Dashboard from "../components/Dashboard.vue";

import moxios from 'moxios'
import axios from 'axios';
import VueAxios from 'vue-axios'
import flushPromises from 'flush-promises'
// import VueRouter from 'vue-router';
// import router from '../router.js'
import store from '../store.js'


const localVue = createLocalVue()
// localVue.use(VueRouter)
localVue.use(VueAxios, axios);

beforeEach(function () {
  moxios.install()
})

afterEach(function () {
  moxios.uninstall()
})


describe('Dashboard', () => {
  let wrapper = mount(Dashboard, {
    localVue,
    axios,store
  });
  it('shows title', () => {
    expect(wrapper.html()).toContain("Odśwież")
    expect(wrapper.html()).toContain("ogłoszenia")
    expect(wrapper.html()).toContain("progress")
  })

  it('loads messages', (done) => {
    wrapper.find( '#Oddział').trigger('click');
    moxios.wait(function () {
      let request = moxios.requests.mostRecent()
      request.respondWith({
        status: 200,
        response: {
          data: {
            data:{
              author:12,
              body:"Spotkanie dzisiaj na Messengerze o 19! ;)",
              division:1,
              id:13,
              modified:1,
              name:"Karolina",
              published_at:"2020-04-27 11:48:33",
              receiver_group:1,
              surname:"Fk",
              title:"Witajcie"
              
            }
          }
        }
      }).then(function () {
        const card =wrapper.find('.card');
        expect(card.html()).not.toContain('progress');
        expect(card.html()).toContain('Witajcie');
        expect(card.html()).toContain('Spotkanie dzisiaj na Messengerze o 19! ;)');
        expect(card.html()).toContain('Karolina');
        expect(card.html()).toContain('edytowana');
        done()
      })
    })
  })

  
})