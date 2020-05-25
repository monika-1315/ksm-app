import { mount, createLocalVue } from 'vue-test-utils';
import expect from 'expect';
import moxios from 'moxios'

import Contact from "../components/Contact.vue";

import axios from 'axios';
import VueAxios from 'vue-axios'

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

    it('shows emails', (done) => {
        wrapper.vm.axios.get("/api/getManagement").then(
            function(response) {
                wrapper.vm.management = response.data;
                wrapper.vm.isProgress=false;
            }.bind(this)
          );
        moxios.wait(function () {
            let request = moxios.requests.mostRecent()
            request.respondWith({
                status: 200,
                response: {
                    success: false,
                    data: {function_mail: "ksmdl.skarbnik@gmail.com",
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