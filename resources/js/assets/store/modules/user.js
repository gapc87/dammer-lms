export const SET_ALL_USER_DATA = 'SET_ALL_USER_DATA';

export default {
    namespaced: true,

    state: {
        user: {},
        token: null,
        authenticated: false
    },

    getters: {
        getUser: state => state.user,
        getToken: state => state.token,
        getAuthenticated: state => state.authenticated,
        role: state => role => state.authenticated && state.user.role_id
    },

    mutations: {
        [SET_ALL_USER_DATA]: (state, payload) => {
            state.user = payload.user;
            state.token = payload.token;
            state.authenticated = payload.authenticated;
        }
    },

    actions: {
        setStateUserFromLocalStorage: (context) => {
            context.commit(SET_ALL_USER_DATA, {
                user: auth.user,
                token: auth.token,
                authenticated: auth.check(),
            });
        },
        login: (context, data) => {
            return api.call('post', '/api/login', data)
                .then(response => {
                    context.commit(SET_ALL_USER_DATA, {
                        user: response.data.user,
                        token: response.data.token,
                        authenticated: true,
                    });

                    auth.login(response.data.user, response.data.token);
                });
        },
        logout: context => {
            return api.call('post', '/api/logout')
                .then(() => {
                    context.commit(SET_ALL_USER_DATA, {
                        user: {},
                        token: null,
                        authenticated: false
                    });

                    auth.logout();
                });
        }
    }
};
