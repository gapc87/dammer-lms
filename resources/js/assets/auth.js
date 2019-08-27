class Auth {
    constructor() {
        let userData = window.localStorage.getItem('user');
        this.user = userData ? JSON.parse(userData) : null;
        this.token = window.localStorage.getItem('token');

        this.setHeaders();
    }

    login (user, token) {
        window.localStorage.setItem('user', JSON.stringify(user));
        window.localStorage.setItem('token', token);

        this.token = token;
        this.user = user;

        this.setHeaders();
    }

    check () {
        return !! this.token;
    }

    logout() {
        this.user = null;
        this.token = null;
        delete axios.defaults.headers.common["Authorization"];
        window.localStorage.removeItem('user');
        window.localStorage.removeItem('token');
    }

    setHeaders() {
        if (this.check()) {

            axios.defaults.headers.common['Authorization'] = 'Bearer ' + this.token;

            // Headers para Datatables
            $.ajaxSetup({ headers: { 'Authorization': 'Bearer ' + this.token }});
        }
    }
}

export default Auth;
