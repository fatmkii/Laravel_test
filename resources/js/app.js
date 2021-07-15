import Vue from 'vue'
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'
import '../css/app.scss'
import VueRouter from 'vue-router'
Vue.use(BootstrapVue)
Vue.use(IconsPlugin)
Vue.use(VueRouter)

function requireAuth(to, from, next) {
    function proceed() {
        // 如果用户信息已经加载并且不为空则说明该用户已登录，可以继续访问路由，否则跳转到首页
        // 这个功能类似 Laravel 中的 auth 中间件
        if (store.getters.getUserLoadStatus() === 2) {
            if (store.getters.getUser != '') {
                next();
            } else {
                next('/home');
            }
        }
    }

    if (store.getters.getUserLoadStatus() !== 2) {
        // 如果用户信息未加载完毕则先加载
        store.dispatch('loadUser');

        // 监听用户信息加载状态，加载完成后调用 proceed 方法继续后续操作
        store.watch(store.getters.getUserLoadStatus, function () {
            if (store.getters.getUserLoadStatus() === 2) {
                proceed();
            }
        });
    } else {
        // 如果用户信息加载完毕直接调用 proceed 方法
        proceed()
    }
}

window.Vue = Vue
window.axios = require('axios')
axios.defaults.withCredentials = true; // 在全局 axios 实例中启用 withCredentials 选项

//测试用的
Vue.component('axios_test', require('../vue/test/axios_test.vue').default);
Vue.component('parent_com', require('../vue/test/parent_com.vue').default);
Vue.component('children_com', require('../vue/test/children_com.vue').default);

//通用导航栏
Vue.component('navigation', require('../vue/navigation.vue').default);


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
    router
}).$mount('#app')
