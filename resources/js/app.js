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
import router from './routes.js'
import "rfs";

window.sha256 = require('js-sha256')

window.axios = require('axios')
axios.defaults.withCredentials = false; // 在全局 axios 实例中启用 withCredentials 选项
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
//axios拦截器
axios.interceptors.response.use(
    (response) => {
        return response
    },
    error => {
        if (error.response.status === 401) {
            localStorage.clear('Binggan')   //如果遇到401错误(用户未认证)，就清除Binggan和Token
            localStorage.clear('Token')
            axios.defaults.headers.Authorization = "";
            alert('此页面需先登录喔！');
            window.location.href = '/login' //统一跳转到登陆页面
        }
        throw error
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


router.afterEach((to, from) => {
    window.scrollTo(0, 0);
})

const app = new Vue({
    router,
    store
}).$mount('#app')
