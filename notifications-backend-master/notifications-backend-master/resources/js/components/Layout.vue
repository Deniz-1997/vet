<template>
    <main class="py-4">
        <div>
            <div class="wr-column">
                <menu-component></menu-component>
                <router-view></router-view>
            </div>
            <ul class="menu-column__user-menu-dr">
                <li>
                    <a href="#" v-on:click="this.logout">
                        Выйти из системы
                    </a>
                </li>
            </ul>
            <notifications classes="vue-notification success" group="success" position="bottom left"/>
            <notifications classes="vue-notification warning" group="warning" position="bottom left"/>
            <notifications classes="vue-notification error" group="error" position="bottom left"/>
        </div>
    </main>
</template>

<script>
    import router from "../routes";

    export default {
        name: "Layout",
        data: () => ({
            authenticated: window.auth.check(),
            user: window.auth.user
        }),

        methods: {
            check() {
                return true;
            },
            logout() {
                $('ul.menu-column__user-menu-dr').hide();

                window.auth.login(null, null);

                Event.$emit('hideMenu');

                router.push('/login');
            }
        },

        created() {
        },

        mounted() {

            Event.$on('userLoggedIn', () => {
                this.authenticated = true;
                this.user = window.auth.user;
            });
        },
    }
</script>

<style scoped>

</style>
