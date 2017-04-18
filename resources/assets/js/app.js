
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

import VueRouter from "vue-router"

import Vuex from "vuex"

window.HTTP = axios.create({
    baseURL: 'http://127.0.0.1:8000/api/'
});

Vue.use(VueRouter);

Vue.use(Vuex);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const welcome = Vue.component('welcome', require('./components/Welcome.vue'));

const login = Vue.component('login', require('./components/Auth/Login.vue'));

const register = Vue.component('register', require('./components/Auth/Register.vue'));

const appBlock = Vue.component('appBlock', require('./components/AppBlock.vue'));

const asideBlock = Vue.component('asideBlock', require('vue-strap/src/Aside.vue'));

const sidebar = Vue.component('sidebar', require('./components/Sidebar.vue'));

const wall = Vue.component('wall', require('./components/Wall.vue'));

const actions = Vue.component('actions', require('./components/Actions.vue'));

const postContainer = Vue.component('postContainer', require('./components/Post.vue'));

const commentContainer = Vue.component('commentContainer', require('./components/Comment.vue'));

const router = new VueRouter({
    routes: [
        {
            path: '/',
            component: welcome
        },
        {
            path: '/login',
            component: login
        },
        {
            path: '/register',
            component: register
        },
        {
            path: '/wall',
            component: appBlock
        },
        {
            path: '/wall/:userId:',
            component: appBlock
        },
    ]
});

const store = new Vuex.Store({
    strict: true,
    state: {
        user: null,
        authenticated: false
    },
    mutations: {
        destroyLogin (state, payload) {
            state.user = null;
            state.authenticated = false;
        },
        setLogin (state, payload) {
            console.log(payload);
            state.user = payload.user;
            state.authenticated = true;
        }
    },
    actions: {
        userHasLoggedOut ({commit}) {
            commit('destroyLogin');
        },
        userHasLoggedIn ({commit, state}, payload) {
            commit('setLogin', payload);
        }
    }
});

const app = new Vue({
    el: '#app',
    store,
    router: router,
    ready: function () {

    },
    data: function () {
        return {}
    },
    methods: {
        logout: function () {
            HTTP.post('/logout');
            this.$store.dispatch('userHasLoggedOut');
        }
    }
});