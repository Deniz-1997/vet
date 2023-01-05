<template>
    <div>
        <notifications classes="vue-notification success" group="login_success" position="bottom left"/>
        <notifications classes="vue-notification error" group="login_error" position="bottom left"/>
        <div class="autorize-wr">
            <div class="autorize">
                <div class="autorize__logo"><img alt="" src='/img/logo.svg'></div>
                <div class="autorize__header">
                    Комплексное облачное решение <br>для ветеринарии
                </div>
                <div class="autorize__form">
                    <div class="autorize__form-h">Авторизация</div>
                    <form @submit.prevent="login" action="">
                        <div class="autorize__form-row">
                            <input class="autorize__form-inp" placeholder="Почта или Телефон" type="text"
                                   v-model="email">
                        </div>
                        <div class="autorize__form-row">
                            <input class="autorize__form-inp" placeholder="Пароль" type="password" v-model="password">
                        </div>
                        <div class="autorize__form-btn"><input type="submit" value="ВОЙТИ"></div>
                    </form>
                </div>
            </div>
        </div>

        <div class="autorize-footer">
            <div class="autorize-footer__head">Служба поддержки</div>
            <div class="autorize-footer__phone"><a href="tel:88005552404">8 800 555 24 04</a></div>
            <div class="autorize-footer__mob"><a href="mailto:help@corvet.ru">help@corvet.ru</a></div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "Login",
        data() {
            return {
                email: '',
                password: '',
            };
        },

        created() {
            if (window.auth.check()) {
                this.$router.go('/dashboard');
            }
        },

        methods: {
            login() {
                let data = {
                    email: this.email,
                    password: this.password
                };

                let loader = this.$loading.show({
                    color: '#0c51b6'
                });


                window.axios.post('/login', data)
                    .then(({data}) => {
                        console.log(data);
                        if (data.status) {
                            auth.login(data.data.access_token, data.data);
                            window.Vue.notify({
                                group: 'login_success',
                                title: 'Аутентификация прошла успешно',
                                text: 'Вы успешно аутентифицировались в систему'
                            });
                            let this_ = this;
                            setTimeout(function () {
                                this_.$router.go('/login');
                            }, 500);
                        } else {
                            loader.hide();
                            window.Vue.notify({
                                group: 'login_error',
                                title: 'Ошибка аутентификации',
                                text: 'Некорректно указан логин или пароль'
                            });
                        }
                    })
                    .catch(({response, error}) => {
                        loader.hide();
                        let msg = 'Произошла ошибка на стороне сервера при авторизации пользователя';

                        if (typeof response.data.errors !== undefined) {
                            $.each(response.data.errors, (i, v) => {
                                if (typeof v !== 'string') {
                                    msg = '';

                                    $.each(v, (o, a) => {
                                        msg += a;
                                    });
                                } else {
                                    msg = v;
                                }
                            });
                        }

                        window.Vue.notify({
                            group: 'login_error',
                            title: 'Ошибка аутентификации',
                            text: msg
                        });
                    });
            }
        }
    }
</script>

<style scoped>

</style>
