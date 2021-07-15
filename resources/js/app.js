import Vue from 'vue'
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'
import '../css/app.scss'
import VueRouter from 'vue-router'
Vue.use(BootstrapVue)
Vue.use(IconsPlugin)
Vue.use(VueRouter)

window.Vue = Vue
window.axios = require('axios')

//测试用的
Vue.component('axios_test', require('../vue/test/axios_test.vue').default);
Vue.component('parent_com', require('../vue/test/parent_com.vue').default);
Vue.component('children_com', require('../vue/test/children_com.vue').default);

//通用导航栏
Vue.component('navigation', require('../vue/navigation.vue').default);


const routes = [
    {
        path: '/',
        component: (resolve) => require(['../vue/homepage/homepage.vue'], resolve),
    },
    {
        path: '*',
        component: (resolve) => require(['../vue/homepage/homepage.vue'], resolve),
    },
    {
        path: '/forum/:id',
        component: (resolve) => require(['../vue/forum/forum.vue'], resolve),
    },
]

const router = new VueRouter({
    mode: 'history',
    routes // (缩写) 相当于 routes: routes
})

router.afterEach((to, from) => {
    window.scrollTo(0, 0);
})

const app = new Vue({
    router
}).$mount('#app')
