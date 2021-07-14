import Vue from 'vue'
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'
import '../css/app.scss'
Vue.use(BootstrapVue)
Vue.use(IconsPlugin)
window.Vue = Vue
window.axios = require('axios')

//测试用的
Vue.component('navigation', require('../vue/navigation.vue').default);
Vue.component('axios_test', require('../vue/axios_test.vue').default);
Vue.component('parent_com', require('../vue/parent_com.vue').default);
Vue.component('children_com', require('../vue/children_com.vue').default);

//主页homepage
Vue.component('homepage_bulletin', require('../vue/homepage/homepage_bulletin.vue').default);
Vue.component('homepage_forums', require('../vue/homepage/homepage_forums.vue').default);


//最后挂载Vue
const app = new Vue({
    el: '#app',
});
