const SET_USERS = 'SET_USERS';

export default {
    namespaced: true,

    state: {
        users: {}
    },

    getters: {
        getUsers: state => state.users
    },

    mutations: {
        [SET_USERS]: (state, payload) => {
            state.users = payload.data;
        }
    },

    actions: {
        users: (context, data) => {
            context.commit(SET_USERS, data);
        }
    }
}
