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
axios.defaults.withCredentials = false; // 在全局 axios 实例中启用 withCredentials 选项
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
//axios拦截器
axios.interceptors.response.use(
    (response) => {
        return response
    },
    err => {
        if (err.response.status === 401) {
            window.location.href = '/login' //如果遇到401错误(用户未认证)，就统一跳转到登陆页面
        }
        console.log(new Error(err))
    }
);




//测试用的
Vue.component('axios_test', require('../vue/test/axios_test.vue').default);
Vue.component('parent_com', require('../vue/test/parent_com.vue').default);
Vue.component('children_com', require('../vue/test/children_com.vue').default);

//全局通用导航栏
Vue.component('navigation', require('../vue/navigation.vue').default);
//全局app.vue，用来做一些全剧请求（forums，user等）
Vue.component('app', require('../vue/app.vue').default);
//全局通用底部
Vue.component('footer_navi', require('../vue/footer_navi.vue').default);


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
        path: '/forum/:forum_id/:page?',
        name: 'forum',
        props: route => ({
            forum_id: parseInt(route.params.forum_id),
            page: parseInt(route.params.page),
        }),
        component: (resolve) => require(['../vue/forum/forum_page.vue'], resolve),
    },
    {
        path: '/thread/:thread_id/:page?',
        name: 'thread',
        props: route => ({
            thread_id: parseInt(route.params.thread_id),
            page: parseInt(route.params.page),
        }),
        component: (resolve) => require(['../vue/thread/thread_page.vue'], resolve),
    },
    {
        path: '/new_thread/:forum_id',
        name: 'new_thread',
        props: route => ({
            forum_id: parseInt(route.params.forum_id),
        }),
        component: (resolve) => require(['../vue/thread/new_thread.vue'], resolve),
    },
    {
        path: '/user-center',
        name: 'user-center',
        component: (resolve) => require(['../vue/user/user_center.vue'], resolve),
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
