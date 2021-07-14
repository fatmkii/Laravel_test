import Vue from 'vue'
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'
import '../css/app.scss'
Vue.use(BootstrapVue)
Vue.use(IconsPlugin)
window.Vue = Vue
window.axios = require('axios')

//测试用的
Vue.component('axios_test', require('../vue/test/axios_test.vue').default);
Vue.component('parent_com', require('../vue/test/parent_com.vue').default);
Vue.component('children_com', require('../vue/test/children_com.vue').default);

//通用导航栏
Vue.component('navigation', require('../vue/navigation.vue').default);

//主页homepage
Vue.component('homepage_bulletin', require('../vue/homepage/homepage_bulletin.vue').default);
Vue.component('homepage_forums', require('../vue/homepage/homepage_forums.vue').default);


//最后挂载Vue
const app = new Vue({
    el: '#app',
});
