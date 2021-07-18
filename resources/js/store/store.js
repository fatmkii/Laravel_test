import Vue from 'vue'
import Vuex from 'vuex'
Vue.use(Vuex)

const module_user = {
    state: () => ({
        Token: '',
        Binggan: '',
        LoginStatus: false,
    }),
    mutations: {
        Token_set(state, payload) {
            state.Token = payload
        },
        Binggan_set(state, payload) {
            state.Binggan = payload
        },
        LoginStatus_set(state, payload) {
            state.LoginStatus = payload
        },
    },
    actions: {},
    getters: {}
}

const module_forums = {
    state: () => ({
        ForumsData: '',
    }),
    mutations: {
        ForumsData_set(state, payload) {
            state.ForumsData = payload
        }
    },
    getters: {
        ForumData: (state) => (forum_id) => {
            if (state.ForumsData) {
                return state.ForumsData.find(ForumData => ForumData.id == forum_id)
            }
        },
        ForumsCount: (state) => {
            if (state.ForumsData) {
                return state.ForumsData.length
            }
        }
    },
    actions: {}
}


const module_threads = {
    state: () => ({
        ThreadsData: '',
        ThreadLoadStatus: 0,
    }),
    mutations: {
        ThreadsData_set(state, payload) {
            state.ThreadsData = payload
        },
        ThreadsLoadStatus_set(state, payload) {
            state.ThreadLoadStatus = payload
        }
    },
    getters: {
        ThreadsLastPage: (state) => {
            // if (state.ThreadsData) {
            //     return 4
            // }
            return 4
        }
    },
    actions: {}
}


export default new Vuex.Store({
    modules: {
        Forums: module_forums,
        User: module_user,
        Threads: module_threads,
    }
})
