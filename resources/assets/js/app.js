
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

import VueRouter from "vue-router";

Vue.use(VueRouter);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const welcome = Vue.component('welcome', require('./components/Welcome.vue'));

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
            path: '/wall',
            component: appBlock
        },
        {
            path: '/wall/:userId:',
            component: appBlock
        },
    ]
});

const app = new Vue({
    el: '#app',
    router: router
});
