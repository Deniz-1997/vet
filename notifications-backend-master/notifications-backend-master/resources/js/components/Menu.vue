<template>
    <div class="menu-column" v-show="show">
        <div class="menu-column__body">
            <div class="menu-column__head">
                <div class="menu-column__logo"><img alt="" src="/img/logo-white.svg"></div>
                <div class="menu-column__ms" style="display: none">
                    <div class="menu-column__ms-ico">
                        <a href="">
                            <svg height="24px" version="1.1"
                                 viewBox="0 0 20 24" width="20px" xmlns="http://www.w3.org/2000/svg"
                                 xmlns:xlink="http://www.w3.org/1999/xlink">
                                <defs/>
                                <g fill="none" fill-rule="evenodd" stroke="none" stroke-width="1">
                                    <g fill="#FFFFFF" fill-rule="nonzero"
                                       transform="translate(-250.000000, -38.000000)">
                                        <path
                                            d="M252.272727,59.3 L256.524064,59.3 C256.951872,60.8818182 258.368984,62 260,62 C261.631016,62 263.048128,60.8545455 263.475936,59.3 L267.727273,59.3 C268.983957,59.3 270,58.2636364 270,56.9818182 C270,56.2181818 269.625668,55.4818182 268.983957,55.0454545 C268.368984,54.6090909 267.352941,53.1909091 267.352941,48.4181818 C267.352941,43.8363636 264.705882,41.9 262.647059,41.0818182 C262.673797,40.9454545 262.673797,40.8363636 262.673797,40.7272727 C262.673797,39.2272727 261.470588,38 260,38 C258.529412,38 257.326203,39.2272727 257.326203,40.7272727 C257.326203,40.8363636 257.326203,40.9727273 257.352941,41.0818182 C255.294118,41.9 252.647059,43.8363636 252.647059,48.4181818 C252.647059,53.1909091 251.631016,54.6090909 251.016043,55.0454545 C250.374332,55.4818182 250,56.2181818 250,56.9818182 C250,58.2636364 251.016043,59.3 252.272727,59.3 Z M260,60.3636364 C259.251337,60.3636364 258.582888,59.9272727 258.235294,59.3 L261.737968,59.3 C261.417112,59.9272727 260.748663,60.3636364 260,60.3636364 Z M260.001015,39.8461538 C260.519867,39.8461538 260.834969,40.3403263 260.909091,40.7692308 C260.143167,40.6620047 259.585054,40.7048951 259.090909,40.7692308 C259.140324,40.3403263 259.482163,39.8461538 260.001015,39.8461538 Z M254.855511,48.4281906 C254.855511,44.8882966 257.009605,43.521165 258.804683,43.0084907 L258.852552,42.9840777 C258.852552,42.9840777 259.355174,42.8131862 260.049271,42.8131862 C260.408286,42.8131862 260.815171,42.8620124 261.222055,42.9840777 C263.041067,43.496752 265.195161,44.8638835 265.195161,48.4037776 C265.195161,50.9915622 265.262863,52.4228284 265.909091,53.6923077 L254.090909,53.6923077 C254.761072,52.4472415 254.855511,51.0159753 254.855511,48.4281906 Z M253.131313,55.5384615 L266.868687,55.5384615 C266.868687,55.5384615 267.727273,56 267.727273,56.4615385 C267.727273,57.1872079 267.222222,57.3846154 266.868687,57.3846154 L253.131313,57.3846154 C252.777778,57.3846154 252.272727,57.1872079 252.272727,56.4615385 C252.272727,56 253.131313,55.5384615 253.131313,55.5384615 Z"
                                            id="Shape"/>
                                    </g>
                                </g>
                            </svg>
                            <span>3</span>
                        </a>
                    </div>
                </div>
            </div>

            <div class="menu-column__user">
                <div class="menu-column__user-menu">
                    <div class="menu-column__user-menu-ico">
                        <a href="">
                            <svg height="16px" viewBox="0 0 4 16"
                                 width="4px" xmlns="http://www.w3.org/2000/svg"
                                 xmlns:xlink="http://www.w3.org/1999/xlink">
                                <defs/>
                                <g fill="none" fill-rule="evenodd" stroke="none" stroke-width="1">
                                    <g fill="#FFFFFF" transform="translate(-258.000000, -99.000000)">
                                        <g transform="translate(248.000000, 95.000000)">
                                            <g transform="translate(10.000000, 4.000000)">
                                                <path
                                                    d="M2,4 C0.8954305,4 0,3.1045695 0,2 C0,0.8954305 0.8954305,0 2,0 C3.1045695,0 4,0.8954305 4,2 C4,3.1045695 3.1045695,4 2,4 Z M2,10 C0.8954305,10 0,9.1045695 0,8 C0,6.8954305 0.8954305,6 2,6 C3.1045695,6 4,6.8954305 4,8 C4,9.1045695 3.1045695,10 2,10 Z M2,16 C0.8954305,16 0,15.1045695 0,14 C0,12.8954305 0.8954305,12 2,12 C3.1045695,12 4,12.8954305 4,14 C4,15.1045695 3.1045695,16 2,16 Z"
                                                    id="Combined-Shape"/>
                                            </g>
                                        </g>
                                    </g>
                                </g>
                            </svg>
                        </a>
                    </div>
                </div>
                <div class="menu-column__user-name">
                    <a href="#">{{ name }}</a>
                </div>
            </div>

            <div id="list-menu">
                <div v-show="subadmin">
                    <p style="font-size: 1.2em; color: white; margin-bottom: 1em;">Ваши каналы: </p>
                    <div class="menu-column__slider" v-for="channel in channels">
                        <a :class="{ active : channel_id === channel.id }"
                           v-bind:data-id="channel.id"
                           class="menu-column__slider-h menu-channel"
                           @click="selectChannel(channel)"
                           v-bind:data-name="channel.name">
                            {{ channel.name }}</a>
                    </div>
                </div>

                <div :class="activeMenu(route)"
                     class="menu-column__slider"
                     v-for="route in routes">
                    <a class="menu-column__slider-h" href="#">
                        {{ route.name }}
                    </a>

                    <ul
                        :class="activeSubMenu(route)"
                        class="menu-column__slider-lnk">
                        <li v-for="child in route.group">
                            <a
                                :class="activeLinkMenu(child)"
                                v-bind:href="child.path"
                                v-on:click="activeClickLinkMenu">{{ child.menuName }}</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="menu-column__footer">
            <div class="menu-column__copyrighted">
                © КОРВет все права защищены
            </div>
        </div>
    </div>
</template>

<script>

    export default {
        name: "Menu",
        data: () => ({
            channel_id: 0,
            channels: [],
            name: null,
            show: false,
            subadmin: false
        }),

        watch: {
            changeActiveChannel(v) {
                console.log(this.activeChannel);
            },
        },
        computed: {
            routes() {
                if (window.auth.user !== null && typeof window.auth.user.roles !== "undefined") {

                    if (typeof window.auth.user.roles === "undefined") {
                        return [];
                    }

                    return this.getRoles();
                }
            },
        },

        mounted() {
            Event.$on('hideMenu', () => {
                this.show = false;
            });
        },
        created() {
            if (auth.user !== null) {
                this.show = true;
                this.name = auth.user.name;

                this.channel_id = window.localStorage.getItem('channel_id');

            } else {
                this.show = false;
            }
        },
        methods: {

            getRoles() {
                let roles = window.auth.user.roles.map(i => {
                    return i.name;
                });

                if (roles.indexOf('administrator') === -1) {
                    this.subadmin = true;

                    api.channels.administrators.get({
                        paginator: false,
                        where: JSON.stringify([
                            ['user_id', '=', window.auth.user.id]
                        ]),
                        with: JSON.stringify(['channel'])
                    }).then(response => {
                        if (response.status) {
                            if (response.data.length > 0) {
                                this.channel_id = response.data[0].channel.id;
                                window.localStorage.setItem('channel_id', this.channel_id);
                                window.localStorage.setItem('channel_name', response.data[0].channel.name);
                                Event.$emit('changeChannelId');

                                this.channels = response.data.map(e => {
                                    return e.channel;
                                });
                            }
                        }
                    });
                    return [
                        {
                            name: 'Словарь',
                            group_name: 'dictionary',
                            group: [
                                {
                                    menuName: 'Группы пользователей',
                                    path: '#/dictionary/group-user/',
                                }
                            ]
                        },
                        {
                            name: 'Пользователи',
                            group_name: 'users',
                            group: [
                                {
                                    menuName: 'Список',
                                    path: '#/users/list/',
                                    icon: 'channel',
                                }
                            ]
                        },
                    ];
                } else {
                    return [
                        {
                            name: 'Каналы',
                            group_name: 'channel',
                            group: [
                                {
                                    menuName: 'Список',
                                    path: '#/channel/list/',
                                },
                                {
                                    menuName: 'API ключи',
                                    path: '#/channel/api/',
                                },
                                {
                                    menuName: 'События',
                                    path: '#/channel/events/',
                                },
                                {
                                    menuName: 'Пользователи',
                                    path: '#/channel/users/',
                                },

                                {
                                    menuName: 'Администраторы',
                                    path: '#/channel/administrators/',
                                },
                            ]
                        },
                        {
                            name: 'Оповещения',
                            group_name: 'notifications',
                            group: [
                                {
                                    menuName: 'Список',
                                    path: '#/notifications/list/',
                                },
                                {
                                    menuName: 'События по оповещениям',
                                    path: '#/notifications/events/',
                                },
                                {
                                    menuName: 'На отправку/Отправлено',
                                    path: '#/notifications/sends/',
                                },
                            ]
                        },
                        {
                            name: 'Шаблоны',
                            group_name: 'templates',
                            group: [
                                {
                                    menuName: 'Список',
                                    path: '#/templates/list/',
                                    icon: 'channel',
                                },
                                {
                                    menuName: 'Группы пользователей',
                                    path: '#/templates/group/',
                                    icon: 'channel',
                                }
                            ]
                        },
                        {
                            name: 'События',
                            group_name: 'events',
                            group: [
                                {
                                    menuName: 'Список',
                                    path: '#/events/list/',
                                    icon: 'channel',
                                },
                                {
                                    menuName: 'Шаблоны',
                                    path: '#/events/template/',
                                    icon: 'channel',
                                }
                            ]
                        },
                        {
                            name: 'Пользователи',
                            group_name: 'users',
                            group: [
                                {
                                    menuName: 'Список',
                                    path: '#/users/list/',
                                    icon: 'channel',
                                },
                                {
                                    menuName: 'Устройства',
                                    path: '#/users/devices/',
                                    icon: 'channel',
                                }
                            ]
                        },
                        {
                            name: 'Словарь',
                            group_name: 'dictionary',
                            group: [
                                {
                                    menuName: 'Организации',
                                    path: '#/dictionary/organizations/',
                                },
                                {
                                    menuName: 'Группы пользователей',
                                    path: '#/dictionary/group-user/',
                                }
                            ]
                        },
                    ];
                }
            },

            activeClickLinkMenu(event) {
                $('.menu-column__slider-lnk a.active').removeClass('active');
                $(event.target).addClass('active')
            },

            activeLinkMenu(route, e) {
                return window.location.hash.indexOf(route.path) > -1 ? 'active' : '';
            },

            activeMenu(route) {
                return window.location.hash.indexOf('#/' + route.group_name) > -1 ? 'active' : '';
            },

            activeSubMenu(route) {
                return window.location.hash.indexOf('#/' + route.group_name) > -1 ? 'show-b' : '';
            },

            selectChannel(channel) {
                if (this.$route.name !== 'dashboard') {
                    this.$router.push('/dashboard');
                }

                if (channel.id !== this.channel_id) {
                    this.channel_id = channel.id;
                    window.localStorage.setItem('channel_id', this.channel_id);
                    window.localStorage.setItem('channel_name', $('.menu-channel[data-id="' + this.channel_id + '"]').attr('data-name'));
                    Event.$emit('changeChannelId');
                    $('.menu-column__slider.active').removeClass('active');
                    $('.menu-column__slider-lnk').hide();
                }
            },

            logout() {
                window.auth.login(null, null);
                this.$router.push('/login');
            },

            to(parent_path, path) {
                if (parent_path + '/' + path !== this.$router.currentRoute.path) this.$router.push(parent_path + '/' + path);
            },

            check() {
                return window.auth.check();
            },
        }
    }
</script>

<style scoped>
    .show-b {
        display: block;
    }

    .menu-column__slider-lnk a.active {
        color: #0c51b6 !important;
        font-weight: 900;
    }

    a.menu-column__slider-h.active {
        background-color: white;
        color: black;
    }
</style>
