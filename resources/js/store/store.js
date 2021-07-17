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
        ForumCount: (state) => {
            return state.ForumsData.length
        }
    },
    actions: {}
}



export default new Vuex.Store({
    modules: {
        Forums: module_forums,
        User: module_user
    }
})
