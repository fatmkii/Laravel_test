import Vue from 'vue'
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'
Vue.use(BootstrapVue)
Vue.use(IconsPlugin)
import '../css/app.scss'

window.Vue = Vue
window.axios = require('axios')

Vue.component('navigation', require('../vue/navigation.vue').default);
Vue.component('axios_test', require('../vue/axios_test.vue').default);
Vue.component('homepage_forums', require('../vue/homepage/homepage_forums.vue').default);
Vue.component('parent_com', require('../vue/parent_com.vue').default);
Vue.component('children_com', require('../vue/children_com.vue').default);

const app = new Vue({
    el: '#app',
});
