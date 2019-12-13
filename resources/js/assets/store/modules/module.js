export const SET_MODULE = 'SET_MODULE';
export const SET_TASK = 'SET_TASK';

export default {
    namespaced: true,

    state: {
        module: {
            name: null
        },
        evaluations: {},
        task: {}
    },

    getters: {
        getModule: state => state.module,
        getEvaluations: state => state.evaluations,
        getTask: state => state.task
    },

    mutations: {
        [SET_MODULE]: (state, payload) => {
            state.module = payload.data.module;
            state.evaluations = payload.data.evaluations;
        },

        [SET_TASK]: (state, payload) => {
            state.task = payload.data;
        }
    },

    actions: {
        module: (context, data) => {
            context.commit(SET_MODULE, data);
        },

        task: (context, data) => {
            context.commit(SET_TASK, data);
        },
    }
}
