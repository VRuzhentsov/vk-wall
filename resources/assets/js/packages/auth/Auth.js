export default function (Vue) {
    Vue.auth = {
        setToken: (token, expiration) => {
            localStorage.setItem('token', token);
            localStorage.setItem('expiration', expiration);
        },

        getToken: function () {
            let token = localStorage.getItem('token', token);
            let expiration = localStorage.getItem('expiration', expiration);

            if (!token || !expiration)
                return null;

            if (Date.now() > parseInt(expiration)) {
                this.destroyToken();
                return null;
            } else {
                return token;
            }
        },

        destroyToken: function () {
            localStorage.removeItem('token');
            localStorage.removeItem('expiration');
        },

        isAuthenticated: function () {
            return !!this.getToken();
        }
    };

    Object.defineProperties(Vue.prototype, {
        $auth: {
            get: function () {
                return Vue.auth;
            }
        }
    })
}