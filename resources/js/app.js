require('./bootstrap');
import "bootstrap/dist/css/bootstrap.min.css";

window.Vue = require('vue');

//import App from './components/App.vue';
import Welcome from './components/Welcome.vue';
import Page from './components/Page.vue';


const app = new Vue({
  el: '#app',
  components: {
    Welcome,
    Page
  }
});