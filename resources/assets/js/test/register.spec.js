import { mount } from 'vue-test-utils';
import expect from 'expect';

import Register from "../components/Register.vue";
// import Login from "../components/Login.vue";
import App from "../App.vue";
import Home from "../components/Home.vue";


describe('Home', () => {
    let wrapper = mount(Home);
    it('shows welcoming text', () => {
        expect(wrapper.html()).toContain("Witamy w aplikacji Katolickiego Stowarzyszenia Młodzieży Diecezji Legnickiej")
    })
    
    // it('starts with no division selected', ()=>{
    //     expect(wrapper.vm.division).toBe(0);
    // expect(wrapper.vm.loginError).toBe(false);
    // })
})