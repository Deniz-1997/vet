import axios from 'axios';
import router from "./routes";
import api from "./api";

class Auth {
    constructor() {
        this.token = window.localStorage.getItem('token');
        let userData = window.localStorage.getItem('user');
        this.user = userData ? JSON.parse(userData) : null;
        if (this.token) {
            axios.defaults.headers.common['api_token'] = this.token;
            this.getUser();
        }
    }

    login(token, user) {
        if (token == null) {
            window.localStorage.removeItem('token');
            this.token = null;
        } else {
            window.localStorage.setItem('token', token);
            window.axios.defaults.headers.common['api_token'] = token;
            this.token = token;

            if (this.token) {
                window.axios.defaults.headers.common['api_token'] = token;
                this.getUser();
            }
        }

        if (user == null) {
            this.user = null;
            window.localStorage.removeItem('user');
        } else {
            this.user = user;
            window.localStorage.setItem('user', JSON.stringify(user));
        }

        Event.$emit('userLoggedIn');
    }

    check() {
        return !!this.token;
    }

    getUser() {
        api.call(this).user.get.get()
            .then(response => {
                if (response.status) {
                    this.user = response.data;
                    window.localStorage.setItem('user', JSON.stringify(response.data));
                } else {
                    this.user = null;
                    window.localStorage.removeItem('user');
                }

                Event.$emit('userLoggedIn');
            })
            .catch(error => {
                window.Vue.notify({
                    group: 'login',
                    classes: 'vue-notification error',
                    title: 'Ошибка авторизации',
                    text: 'Произошла ошибка на стороне сервера при авторизации пользователя'
                });

                this.logout();

                setTimeout(function () {
                    window.location.reload()
                }, 2000);
            });
    }

    logout() {
        window.auth.login(null, null);

        router.push('/login');
    }
}

export default new Auth();
