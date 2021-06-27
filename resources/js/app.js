require('./bootstrap');
 
window.Vue = require('vue');
window.Bus = new Vue();

import Vue from 'vue';
import VueAxios from 'vue-axios';
import axios from 'axios';
import App from './components/App.vue';
import VueSweetalert2 from 'vue-sweetalert2'; 
import 'sweetalert2/dist/sweetalert2.min.css';
import router from './router';
// import vClickOutside from 'v-click-outside';
 import BootstrapVue from 'bootstrap-vue';
Vue.component('pagination', require('laravel-vue-pagination')); 
Vue.use(VueSweetalert2);
Vue.use(VueAxios, axios);
Vue.use(BootstrapVue);
// Vue.use(vClickOutside)


Vue.config.productionTip = false

new Vue({
  router,
  render: h => h(App)
}).$mount('#app')
