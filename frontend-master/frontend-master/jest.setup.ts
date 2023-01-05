import Vue from 'vue';
import Vuetify from 'vuetify';
import { config } from '@vue/test-utils';
import global from './src/components/global';

Vue.use(Vuetify);
config.stubs = { 'fa-icon': { template: '<i>{{name}}</i>', props: { name: String } } };
Object.entries(global).forEach(([key, component]) => Vue.component(key, component));
