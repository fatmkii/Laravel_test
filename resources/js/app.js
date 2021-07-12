import Vue from 'vue'
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'
Vue.use(BootstrapVue)
Vue.use(IconsPlugin)

window.Vue = Vue
window.axios = require('axios')

Vue.component('my-component', require('../vue/app.vue').default);
// Vue.component('hellocomponent', require('../vue/HelloComponent.vue').default);

const app = new Vue({
    el: '#app',
});