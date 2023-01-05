import Vue from 'vue';
import VueRouter from 'vue-router';
import routes from './routes';

import { SETTINGS_KEY as PAGINATION_SETTING_KEY } from '@/components/common/Pagination/consts';
import { SETTINGS_KEY as SEARCH_SETTING_KEY } from '@/components/common/Search/consts';

Vue.use(VueRouter);
const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  scrollBehavior() {
    return { x: 0, y: 0 };
  },
  routes,
});

router.beforeEach((to, { path }, next) => {
  if (!to.path.includes(path)) {
    localStorage.setItem(
      PAGINATION_SETTING_KEY,
      JSON.stringify({ ...JSON.parse(localStorage.getItem(PAGINATION_SETTING_KEY) || '{}'), [path]: undefined })
    );
    localStorage.setItem(
      SEARCH_SETTING_KEY,
      JSON.stringify({ ...JSON.parse(localStorage.getItem(SEARCH_SETTING_KEY) || '{}'), [path]: undefined })
    );
  }

  next();
});

export default router;
