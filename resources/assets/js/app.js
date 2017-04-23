
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

import VueRouter from "vue-router"

import axios from 'axios'

import VueAxios from 'vue-axios'

import Vuex from "vuex"

import Pusher from "pusher-js"

import Echo from "laravel-echo"

import Auth from "./packages/auth/Auth.js"

Vue.use(VueAxios, axios);

Vue.use(VueRouter);

Vue.use(Vuex);

Vue.use(Auth);

Vue.axios.defaults.baseURL = 'http://78.26.174.133/';

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: '02a42a3a470e09dd6bc2',
    cluster: 'eu',
    encrypted: true
});

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const welcome = Vue.component('welcome', require('./components/Welcome.vue'));

const login = Vue.component('login', require('./components/Auth/Login.vue'));

const register = Vue.component('register', require('./components/Auth/Register.vue'));

const appBlock = Vue.component('appBlock', require('./components/AppBlock.vue'));

const sidebar = Vue.component('sidebar', require('./components/Sidebar.vue'));

const wall = Vue.component('wall', require('./components/Wall.vue'));

const actions = Vue.component('actions', require('./components/Actions.vue'));

const commentContainer = Vue.component('commentContainer', require('./components/Comment.vue'));

const navBlock = Vue.component('navBlock', require('./components/NavBlock.vue'));

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
            component: wall,
            meta: {
                requiresAuth: true
            }
        },
        {
            path: '/wall/:userId',
            component: wall,
            name: 'wall',
            meta: {
                requiresAuth: true
            }
        },
    ]
});

const store = new Vuex.Store({
    strict: true,
    state: {
        user: null,
        authenticated: false,
        token: null
    },
    mutations: {
        destroyToken (state, payload) {
            state.token = null;
            state.authenticated = false;
        },
        setToken (state, payload) {
            state.token = payload.token;
            state.authenticated = true;
        },
        updateUser (state, payload) {
            state.user = payload.user;
        }
    },
    actions: {
        userHasLoggedOut ({commit}) {
            commit('destroyToken');
        },
        userHasLoggedIn ({commit, state}, payload) {
            commit('setToken', payload);
            Vue.axios.defaults.headers.common['Authorization'] = 'Bearer ' + state.token;
        },
        userUpdating ({commit, state}, payload) {
            commit('updateUser', payload);
        }
    }
});

router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.requiresAuth)) {
        // this route requires auth, check if logged in
        // if not, redirect to login page.
        if (!store.state.authenticated) {
            next({
                path: '/'
            })
        } else {
            next()
        }
    } else {
        next() // make sure to always call next()!
    }
});

const app = new Vue({
    el: '#app',
    store,
    router,
    created: function () {
        let token = this.$auth.getToken();

        if (token !== null) {
            this.$store.dispatch('userHasLoggedIn', {token: token});
            this.getUser();
        } else {
            this.$store.dispatch('userHasLoggedOut');
        }
    },
    data: function () {
        return {}
    },
    methods: {
        getUser: function () {
            let that = this;
            this.$http.get('/api/user').then(
                function (response) {
                    that.$store.dispatch('userUpdating', {user: response.data.data})
                },
                function (response) {
                    that.$store.dispatch('userHasLoggedOut');
                    that.$auth.destroyToken();
                }
            )
        }
    }
});