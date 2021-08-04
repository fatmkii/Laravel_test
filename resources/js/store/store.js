import Vue from 'vue'
import Vuex from 'vuex'
Vue.use(Vuex)

const module_user = {
    state: () => ({
        Token: '',
        Binggan: '',
        LoginStatus: false,
        AdminStatus: 0,
        AdminForums: [],
        LockedTTL: 0,
        UsePingbici: false,
        TitlePingbici: [],
        ContentPingbici: [],
        MyEmoji: "",
        Emojis: [],
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
        AdminStatus_set(state, payload) {
            state.AdminStatus = payload
        },
        AdminForums_set(state, payload) {
            state.AdminForums = payload
        },
        LockedTTL_set(state, payload) {
            state.LockedTTL = payload
        },
        UsePingbici_set(state, payload) {
            state.UsePingbici = payload
        },
        TitlePingbici_set(state, payload) {
            state.TitlePingbici = payload
        },
        ContentPingbici_set(state, payload) {
            state.ContentPingbici = payload
        },
        MyEmoji_set(state, payload) {
            state.MyEmoji = payload
        },
        Emojis_set(state, payload) {
            state.Emojis = payload
        },
    },
    actions: {},
    getters: {}
}

const module_forums = {
    state: () => ({
        ForumsData: '',
        CurrentForumData: '',
    }),
    mutations: {
        ForumsData_set(state, payload) {
            state.ForumsData = payload
        },
        CurrentForumData_set(state, payload) {
            state.CurrentForumData = payload
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
        ThreadsLoadStatus: 0,
        CurrentThreadData: '',
    }),
    mutations: {
        ThreadsData_set(state, payload) {
            state.ThreadsData = payload
        },
        ThreadsLoadStatus_set(state, payload) {
            state.ThreadsLoadStatus = payload
        },
        CurrentThreadData_set(state, payload) {
            state.CurrentThreadData = payload
        }
    },
    getters: {
        ThreadsLastPage: (state) => {
            if (state.ThreadsData) {
                return state.ThreadsData.last_page
            }
        }
    },
    actions: {}
}

const module_posts = {
    state: () => ({
        PostsData: '',
        PostsLoadStatus: 0,
    }),
    mutations: {
        PostsData_set(state, payload) {
            state.PostsData = payload
        },
        PostsLoadStatus_set(state, payload) {
            state.PostsLoadStatus = payload
        }
    },
    getters: {
        PostsLastPage: (state) => {
            if (state.PostsData) {
                return state.PostsData.last_page
            }
        }
    },
    actions: {}
}


export default new Vuex.Store({
    modules: {
        Forums: module_forums,
        User: module_user,
        Threads: module_threads,
        Posts: module_posts,
    }
})
