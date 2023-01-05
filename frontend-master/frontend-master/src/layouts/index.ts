import component from './default.vue';
import login from './login.vue';

const layouts = {
  login,
  default: component,
};

export type TLayoutType = keyof typeof layouts;
export default layouts;
