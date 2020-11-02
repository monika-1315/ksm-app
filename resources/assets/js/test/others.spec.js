import { mount, createLocalVue } from 'vue-test-utils';
import expect from 'expect';
import moxios from 'moxios'

import Contact from "../components/Contact.vue";
import Home from "../components/Home.vue";
import Divisions from "../components/Divisions.vue";

import axios from 'axios';
import VueAxios from 'vue-axios'
import store from '../store.js'

const localVue = createLocalVue()
localVue.use(VueAxios, axios);


beforeEach(function () {
    moxios.install()
})

afterEach(function () {
    moxios.uninstall()
})

describe('Contact', () => {
    let wrapper = mount(Contact, {
        localVue,
        axios
    });

    it('shows title', () => {
        expect(wrapper.html()).toContain("Skontaktuj się z nami!")
    })

    it('shows loaded emails', (done) => {
        wrapper.vm.getManagement();
        moxios.wait(function () {
            let request = moxios.requests.mostRecent()
            request.respondWith({
                status: 200,
                response: {
                    success: true,
                    data: {
                        function_mail: "ksmdl.skarbnik@gmail.com",
                        function_name: "Skarbnik",
                        name: "Daria",
                        surname: "Pańczyszyn",
                        term_end: null,
                        term_start: "2018-05-16",
                        user_id: 3
                    }
                }
            }).then(function () {
                expect(wrapper.html()).toContain('ksmdl.skarbnik@gmail.com');
                done()
            })
        })
    })
})

describe('Home', () => {
    let wrapper = mount(Home, {
        localVue,
        axios,
        store
    });

    it('shows title', () => {
        const text = wrapper.find('h4').text();
        expect(text).toContain("Witamy w aplikacji");
        expect(text).toContain("Katolickiego Stowarzyszenia Młodzieży");
        expect(text).toContain("Diecezji Legnickiej");
    })
    it('links login and register pages', () => {
        expect(wrapper.html()).toContain('Zaloguj się</button>');
        expect(wrapper.html()).toContain('Zarejestruj się</button>');
    })
})


describe('Divisions', () => {
    let wrapper = mount(Divisions, {
        localVue,
        axios,
        store
    });
    store.state.is_management = 1;

    it('shows header', () => {
        const text = wrapper.find('h2').text();
        expect(text).toContain("Zarządzaj oddziałami");
    })
    it('allows adding new one', () => {
        expect(wrapper.find('button').text()).toBe('Dodaj nowy');
    })
    it('shows all divisions', (done) => {
        wrapper.vm.getDivisions();
        moxios.wait(function () {
            let request = moxios.requests.mostRecent()
            request.respondWith({
                status: 200,
                response:
                    [
                        { "id": 1, "town": "Bolesławiec", "parish": "p.w. św. Cyryla i Metodego", "email": "ksm.boleslawiec@gmail.com", "is_active": 1 },
                        { "id": 2, "town": "Lubin", "parish": "Narodzenia NNMP", "email": "ksm.lubin.nnmp@gmail.com", "is_active": 1 },
                        { "id": 3, "town": "Gościszów", "parish": "p.w. Matki Boskiej Częstochowskiej", "email": "ksmgosciszow@gmail.com", "is_active": 0 },
                        { "id": 4, "town": "Legnica", "parish": "p.w. Matki Bożej Królowej Polski", "email": "oddziallegnica.ksmdl@gmail.com", "is_active": 1 },
                        { "id": 5, "town": "Polkowice", "parish": "św. Michała Archanioła", "email": "polkowiceksm@gmail.com", "is_active": 1 }
                    ]

            }).then(function () {
                expect(wrapper.html()).toContain('Bolesławiec');
                expect(wrapper.html()).toContain('Narodzenia NNMP');
                expect(wrapper.html()).toContain('p.w. Matki Boskiej Częstochowskiej');
                expect(wrapper.html()).toContain('Legnica');
                expect(wrapper.html()).toContain('św. Michała Archanioła');
                expect(wrapper.html()).toContain('NIEAKTYWNY');
                const buttons = wrapper.findAll('button');
                expect(buttons).toHaveLength(6)
                done()
            })
        })
    })

})

