import Vue from 'vue'
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'
import '../css/app.scss'
import VueRouter from 'vue-router'
import Vuex from 'vuex'
Vue.use(BootstrapVue)
Vue.use(IconsPlugin)
Vue.use(VueRouter)
Vue.use(Vuex)
import store from './store/store'

window.Vue = Vue
window.axios = require('axios')
axios.defaults.withCredentials = true; // 在全局 axios 实例中启用 withCredentials 选项

//测试用的
Vue.component('axios_test', require('../vue/test/axios_test.vue').default);
Vue.component('parent_com', require('../vue/test/parent_com.vue').default);
Vue.component('children_com', require('../vue/test/children_com.vue').default);

//通用导航栏
Vue.component('navigation', require('../vue/navigation.vue').default);
//全局app.vue，用来做一些全剧请求（forums，user等）
Vue.component('app', require('../vue/app.vue').default);

const routes = [
    {
        path: '/',
        name: 'homepage',
        component: (resolve) => require(['../vue/homepage/homepage.vue'], resolve),
    },
    {
        path: '*',
        component: (resolve) => require(['../vue/homepage/homepage.vue'], resolve),
    },
    {
        path: '/login',
        name: 'login',
        component: (resolve) => require(['../vue/user/login_page.vue'], resolve),
    },
    {
        path: '/forum/:id',
        name: 'forum',
        props: true,
        component: (resolve) => require(['../vue/forum/forum.vue'], resolve),
    },
]

const router = new VueRouter({
    mode: 'history',
    routes, // (缩写) 相当于 routes: routes
    //控制前端路由的滚动
    scrollBehavior(to, from, savedPosition) {
        if (savedPosition) {
            return savedPosition
        } else {
            return { x: 0, y: 0, behavior: 'smooth', }
        }
    }
})


router.afterEach((to, from) => {
    window.scrollTo(0, 0);
})

const app = new Vue({
    router,
    store
}).$mount('#app')
