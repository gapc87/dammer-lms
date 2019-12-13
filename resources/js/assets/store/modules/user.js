export const SET_ALL_USER_DATA = 'SET_ALL_USER_DATA';

export default {
    namespaced: true,

    state: {
        user: {},
        role: null,
        token: null,
        authenticated: false
    },

    getters: {
        getUser: state => state.user,
        getToken: state => state.token,
        getAuthenticated: state => state.authenticated,
        role: state => state.role,
        hasRole: state => role => state.role === role
    },

    mutations: {
        [SET_ALL_USER_DATA]: (state, payload) => {
            state.user = payload.user;
            state.role = payload.role;
            state.token = payload.token;
            state.authenticated = payload.authenticated;
        }
    },

    actions: {
        setStateUserFromLocalStorage: (context) => {
            context.commit(SET_ALL_USER_DATA, {
                user: auth.user,
                role: auth.user.role.name,
                token: auth.token,
                authenticated: auth.check(),
            });
        },
        login: (context, data) => {
            return api.call('post', '/api/login', data)
                .then(response => {
                    context.commit(SET_ALL_USER_DATA, {
                        user: response.data.user,
                        role: response.data.user.role.name,
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
                        role: null,
                        token: null,
                        authenticated: false
                    });

                    auth.logout();
                });
        }
    }
};
