import { mount, createLocalVue } from 'vue-test-utils';
import expect from 'expect';
import moxios from 'moxios'

import Contact from "../components/Contact.vue";
import Home from "../components/Home.vue";
import Chart from "../components/Chart.vue";

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
        wrapper.vm.axios.get("/api/getManagement").then(
            function (response) {
                wrapper.vm.management = response.data;
                wrapper.vm.isProgress = false;
            }.bind(this)
        );
        moxios.wait(function () {
            let request = moxios.requests.mostRecent()
            request.respondWith({
                status: 200,
                response: {
                    success: false,
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
        const text=wrapper.find('h4').text();
        expect(text).toContain("Witamy w aplikacji");
        expect(text).toContain("Katolickiego Stowarzyszenia Młodzieży");
        expect(text).toContain("Diecezji Legnickiej");
    })
    it('links login and register pages', () => {
        expect(wrapper.html()).toContain('Zaloguj się</button>');
        expect(wrapper.html()).toContain('Zarejestruj się</button>');
    })
})


describe('Chart', () => {
    let wrapper = mount(Chart, {
        localVue,
        axios,
        store
    });

    it('shows headers', () => {
        const text=wrapper.find('h4').text();
        expect(text).toContain("Statystyki autoryzacji użytkowników dla poszczególnych oddziałów");
        expect(wrapper.find('h2').text()).toContain("Statystyki dotyczące liczby członków w oddziałach");
    })
    it('shows loader while reading data', () => {
        expect(wrapper.find('.progress').html()).toContain('indeterminate');
        // expect(wrapper.html()).toContain('Zarejestruj się</button>');
    })
    // it('renders charts', (done) => {
    //     wrapper.vm.getStats();
    //     moxios.wait(function () {
    //         let request = moxios.requests.mostRecent()
    //         request.respondWith({
    //             status: 200,
    //             response:
    //             // []
    //                 [{ "town": "Bolesławiec", "parish": "p.w. św. Cyryla i Metodego", "cnt_all": 6, "cnt_aut": 6 }, { "town": "Gościszów", "parish": "p.w. Matki Boskiej Cz\u0119stochowskiej", "cnt_all": 1, "cnt_aut": 1 }, { "town": "Legnica", "parish": "p.w. Matki Bo\u017cej Królowej Polski", "cnt_all": 2, "cnt_aut": 1 }, { "town": "Lubin", "parish": "Narodzenia NNMP", "cnt_all": 1, "cnt_aut": 1 }, { "town": "Polkowice", "parish": "św. Michała Archanioła", "cnt_all": 2, "cnt_aut": 2 }]
    //         }).then(function () {
    //             // expect(wrapper.html()).toContain('bar-chart');
    //             //   expect(wrapper.html()).toContain('Najważniejsze zebranie członków Kierownictw');
    //             //   expect(wrapper.html()).toContain('2020');
    //             done()
    //         })
    //     })
    // })
})

