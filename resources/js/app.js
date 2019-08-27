require('./bootstrap');

import StoreData from './assets/store/index';
import router from './assets/routes.js';
import VueRouter from 'vue-router';
import Vuex from 'vuex';

import Vue from 'vue';
Vue.use(VueRouter);
Vue.use(Vuex);

export const store = new Vuex.Store(StoreData);

Vue.component('vue-layout', require('./components/Layout').default);

const app = new Vue({
    el: '#app',
    router,
    store
});