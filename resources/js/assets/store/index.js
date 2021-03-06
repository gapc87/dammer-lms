export const SET_LOADING = 'SET_LOADING';
export const SET_ERROR = 'SET_ERROR';
export const SET_HAS_ERROR = 'SET_HAS_ERROR';

import user from './modules/user';
import home from './modules/home';
import module from './modules/module';
import adminUsers from "./modules/adminUsers";

export default {
    modules: {
        user,
        home,
        module,
        adminUsers
    },

    state: {
        loading: false,
        error: null,
        hasError: false
    },

    getters: {
        getLoading: state => state.loading,
        getError: state => state.error,
        hasError: state => state.hasError
    },

    mutations: {
        [SET_LOADING]: (state, payload) => state.loading = payload,
        [SET_ERROR]: (state, payload) => {
            state.error = payload;
            state.hasError = true;
            state.loading = false;
        },
        [SET_HAS_ERROR]: (state, payload) => state.hasError = payload,

    }
}
