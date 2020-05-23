import { mount, createLocalVue } from 'vue-test-utils';
import expect from 'expect';

import Register from "../components/Register.vue";

import axios from 'axios';
import VueAxios from 'vue-axios'

const localVue = createLocalVue()
localVue.use(VueAxios, axios);


describe('Register', () => {
    let wrapper = mount(Register, {
        localVue,
        axios
    });
    it('shows register text', () => {
        expect(wrapper.html()).toContain("rejestruj")
    })
    it('starts with no division selected', () => {
        expect(wrapper.vm.division).toBe(0);
    })
    it('starts with no error', () => {
        expect(wrapper.vm.error).toBe(false);
        expect(wrapper.vm.errors.length).toBe(undefined);
    })
    it('has working data binding', () =>{
        expect(wrapper.vm.name).toBe('');
        type('Monika', 'input[id=name]');
        expect(wrapper.vm.name).toBe('Monika');

        expect(wrapper.vm.surname).toBe('');
        type('Marusiak', 'input[id=surname]');
        expect(wrapper.vm.surname).toBe('Marusiak');

        expect(wrapper.vm.email).toBe('');
        type('monika@m.pl', 'input[id=email]');
        expect(wrapper.vm.email).toBe('monika@m.pl');

        expect(wrapper.vm.password).toBe('');
        type('Marusiak', 'input[id=password]');
        expect(wrapper.vm.password).toBe('Marusiak');

        expect(wrapper.vm.confirmPassword).toBe('');
        type('Marusiak', 'input[id=confirm_password]');
        expect(wrapper.vm.confirmPassword).toBe('Marusiak');

        expect(wrapper.vm.birthdate).toBe('');
        type('2020-02-02', 'input[id=birthdate]');
        expect(wrapper.vm.birthdate).toBe('2020-02-02');
    })

    let type = (text, selector) => {

        let node = wrapper.find(selector);
        node.element.value = text;
        node.trigger('input');
    };
})