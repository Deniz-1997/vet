import { createLocalVue } from '@vue/test-utils';
import Vuetify from 'vuetify';

export const delay = (ms) => new Promise((resolve) => setTimeout(resolve, ms));
export const injectVuetify = () => ({
  localVue: createLocalVue(),
  vuetify: new Vuetify(),
  parentComponent: {
    template: '<v-app><slot /></v-app>',
  },
});
