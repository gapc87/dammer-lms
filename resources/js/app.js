require('./bootstrap');

import VueRouter from 'vue-router';
import Vuex from 'vuex';
import Vue from 'vue';

Vue.use(VueRouter);
Vue.use(Vuex);

Vue.component('vue-layout', require('./components/Layout').default);

const app = new Vue({
    el: '#app'
});