import VueMask from 'v-mask';
import Vue from 'vue';
import 'vue-awesome/icons';
import JsonExcel from 'vue-json-excel';
import Paginate from 'vuejs-paginate';
import VueMoment from 'vue-moment';
import Notifications from 'vue-notification';
import router from '@/router';
import store from '@/store';
import global from '@/components/global';
import App from '@/App.vue';
import vuetify from '@/plugins/vuetify';
import '@/plugins/vue-meta';
import '@/plugins/config';

const Icon = require('vue-awesome/components/Icon').default;
const vClickOutside = require('v-click-outside');

function init() {
  Object.entries(global).forEach(([key, component]) => Vue.component(key, component));

  Vue.config.productionTip = false;

  Vue.use(Notifications);
  Vue.use(vClickOutside);
  Vue.use(VueMask);
  Vue.use(VueMoment);
  Vue.component('DownloadExcel', JsonExcel);
  Vue.component('FaIcon', Icon);
  Vue.component('Paginate', Paginate);

  new Vue({
    router,
    store,
    vuetify,
    render: (h) => h(App),
  }).$mount('#app');
}

init();
