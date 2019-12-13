export const SET_MODULES = 'SET_MODULES';

export default {
    namespaced: true,

    state: {
        modules: {}
    },

    getters: {
        getModules: state => state.modules
    },

    mutations: {
        [SET_MODULES]: (state, payload) => state.modules = payload.data
    },

    actions: {
        modules: (context) => {
            return api.call('get', '/api/modules')
                .then(response => context.commit(SET_MODULES, response.data));
        }
    }
}