require('./bootstrap');
import Vue from 'vue';
import VueRouter from 'vue-router';
import router from './routes.js';
import api from './api.js';
import TableComponentsStrct from './table_components_strct';
import auth from './auth.js';
import vuetify from 'vuetify';
import Notifications from 'vue-notification'
import Datetime from 'vue-datetime'
import 'vue-datetime/dist/vue-datetime.css'
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';

// Translation provided by Vuetify (typescript)
import ru_RU from 'vuetify/src/locale/ru.ts'

window.Event = new Vue();

window.Vue = require('vue');

Vue.use(VueRouter);
Vue.use(vuetify);
Vue.use(Notifications);
Vue.use(Datetime);
Vue.use(Loading);

window.auth = auth;
window.api = api.call(auth);
window.table = TableComponentsStrct.call(window.api);

Vue.component('menu-component', require('./components/Menu.vue').default);
Vue.component('vue-layout', require('./components/Layout.vue').default);
Vue.component('datetime', Datetime(Vue));
Vue.component('table-curd', require('./components/TableCurd.vue').default);

window.Vue.config.devtools = (process.env.MIX_DEV_TOOLS_VUE !== "false");
window.Vue.config.performance = (process.env.MIX_PERFORMANCE_VUE !== "false");
window.Vue.config.productionTip = false;

const app = new window.Vue({
    vuetify: new vuetify({
        lang: {
            locales: { ru_RU },
            current: 'ru_RU',
        }
    }),
    router
}).$mount('#app');
