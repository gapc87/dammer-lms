require('./bootstrap');

import { library } from '@fortawesome/fontawesome-svg-core';
import libraryData from './assets/fontawesome';
import StoreData from './assets/store/index';
import router from './assets/routes.js';
import VueRouter from 'vue-router';
import Vuex from 'vuex';
import Vue from 'vue';

Vue.use(VueRouter);
Vue.use(Vuex);

export const store = new Vuex.Store(StoreData);

library.add(
    libraryData
);

Vue.component('font-awesome-icon', require('@fortawesome/vue-fontawesome').FontAwesomeIcon);
Vue.component('vue-layout', require('./components/Layout').default);

const app = new Vue({
    el: '#app',
    router,
    store
});