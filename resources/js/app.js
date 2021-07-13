import Vue from 'vue'
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'
Vue.use(BootstrapVue)
Vue.use(IconsPlugin)
import '../css/app.scss'

window.Vue = Vue
window.axios = require('axios')

Vue.component('navigation', require('../vue/navigation.vue').default);
Vue.component('axios_test', require('../vue/axios_test.vue').default);

const app = new Vue({
    el: '#app',
});

app.navi_title='title'