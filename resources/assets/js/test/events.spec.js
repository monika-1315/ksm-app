import { mount, createLocalVue } from 'vue-test-utils';
import expect from 'expect';

import Events from "../components/Events.vue";
import moxios, { wait } from 'moxios'
import axios from 'axios';
import VueAxios from 'vue-axios'
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


describe('Events', () => {
  let wrapper = mount(Events, {
    localVue,
    axios,
    store
  });
  it('shows title', () => {
    expect(wrapper.html()).toContain("Wydarzenia:")
  })

  it('points the active category', () => {
    expect(wrapper.html()).toContain('class="activeTab">W diecezji')
  })
  

  it('shows progress bar after clicking refresh button', () => {
    wrapper.find('#refreshBtn').trigger('click');
    
    const progress_bar = wrapper.find('.progress')
    expect(progress_bar.html()).toContain('indeterminate');    

  })

  it('shows events info', (done) => {
    wrapper.find('#refreshBtn').trigger('click');
    moxios.wait(function () {
      let request = moxios.requests.mostRecent()
      request.respondWith({
        status: 200,
        response: 
          [{
              id: 20, division: null, title: "Zjazd Diecezjalny", "about": "Najważniejsze zebranie członków Kierownictw", start: "2020-10-17 09:00:00", end: "2020-10-17 19:30:00", "location": "online - ZOOM", "author": 1
           } ]
          
      }).then(function () {
        expect(wrapper.html()).toContain('Zjazd');
        expect(wrapper.html()).toContain('Najważniejsze zebranie członków Kierownictw');
        expect(wrapper.html()).toContain('2020');
        done()
      })

    })
  })
})