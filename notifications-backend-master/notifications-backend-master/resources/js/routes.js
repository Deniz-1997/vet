import VueRouter from 'vue-router';

let routes = [
    {
        path: '/login',
        component: require('./views/Login.vue').default,
        meta: {middlewareAuth: false}
    },
    {
        name: 'dashboard',
        menuName: 'Dashboard',
        path: '/dashboard',
        icon: 'dashboard',
        component: require('./views/Dashboard.vue').default,
        meta: {middlewareAuth: true}
    },
    {
        name: 'channel_list',
        menuName: 'List',
        path: '/channel/list/',
        icon: 'channel',
        component: require('./views/channel/List.vue').default,
        meta: {middlewareAuth: true},
    },
    {
        name: 'channel_api',
        menuName: 'Api',
        path: '/channel/api/',
        icon: 'channel',
        component: require('./views/channel/Api.vue').default,
        meta: {middlewareAuth: true},
    },
    {
        name: 'channel_events',
        menuName: 'Events',
        path: '/channel/events/',
        icon: 'channel',
        component: require('./views/channel/Events.vue').default,
        meta: {middlewareAuth: true},
    },
    {
        name: 'channel_users',
        menuName: 'Users',
        path: '/channel/users/',
        icon: 'channel',
        component: require('./views/channel/Users.vue').default,
        meta: {middlewareAuth: true},
    },

    {
        name: 'channel_administrator',
        menuName: 'Administrators',
        path: '/channel/administrators/',
        icon: 'channel',
        component: require('./views/channel/Administrators.vue').default,
        meta: {middlewareAuth: true},
    },
    {
        name: 'events_list',
        menuName: 'List',
        path: '/events/list/',
        icon: 'channel',
        component: require('./views/events/List.vue').default,
        meta: {middlewareAuth: true},
    },
    {
        name: 'events_templates',
        menuName: 'Templates',
        path: '/events/template/',
        icon: 'channel',
        component: require('./views/events/Templates.vue').default,
        meta: {middlewareAuth: true},
    },
    {
        name: 'template_group_user',
        menuName: 'Group User',
        path: '/templates/group/',
        icon: 'channel',
        component: require('./views/template/GroupUser.vue').default,
        meta: {middlewareAuth: true},
    },
    {
        name: 'templates_list',
        menuName: 'List',
        path: '/templates/list/',
        icon: 'channel',
        component: require('./views/template/List.vue').default,
        meta: {middlewareAuth: true},
    },

    {
        name: 'users_list',
        menuName: 'Users',
        path: '/users/list/',
        icon: 'channel',
        component: require('./views/user/List.vue').default,
        meta: {middlewareAuth: true},
    },
    {
        name: 'users_devices',
        menuName: 'Devices',
        path: '/users/devices/',
        icon: 'channel',
        component: require('./views/user/Devices.vue').default,
        meta: {middlewareAuth: true},
    },


    {
        name: 'dictionary_organizations',
        path: '/dictionary/organizations/',
        icon: 'channel',
        component: require('./views/dictionary/Organizations.vue').default,
        meta: {middlewareAuth: true},
    },
    {
        name: 'dictionary_group_user',
        menuName: 'Devices',
        path: '/dictionary/group-user/',
        icon: 'channel',
        component: require('./views/dictionary/GroupUser.vue').default,
        meta: {middlewareAuth: true},
    },
    {
        name: 'notifications_list',
        path: '/notifications/list/',
        component: require('./views/notifications/List.vue').default,
        meta: {middlewareAuth: true},

    },
    {
        name: 'notifications_events',
        path: '/notifications/events/',
        component: require('./views/notifications/Event.vue').default,
        meta: {middlewareAuth: true},

    },
    {
        name: 'notifications_sends',
        path: '/notifications/sends/',
        component: require('./views/notifications/Sends.vue').default,
        meta: {middlewareAuth: true},

    },
    {path: "*", component: require('./views/PageError.vue').default}
];

const router = new VueRouter({
    linkExactActiveClass: 'active',
    routes
});

router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.middlewareAuth)) {
        if (!window.auth.check()) {
            next({
                path: '/login',
                query: {redirect: to.fullPath}
            });

            return;
        }
    } else {
        if (to.path === "/login") {
            if (window.auth.check()) {
                router.push('/dashboard')
            }
        } else if (to.path === "/") {
            router.push('/dashboard')
        }
    }

    next();
});

export default router;
