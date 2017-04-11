
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('example', require('./components/Example.vue'));

Vue.component('asideblock', require('vue-strap/src/Aside.vue'));

Vue.component('sidebar', require('./components/Sidebar.vue'));

Vue.component('actions', require('./components/Actions.vue'));

Vue.component('wall', require('./components/Wall.vue'));

Vue.component('comment', require('./components/Comment.vue'));

const app = new Vue({
    el: '#app'
});
