import actions from './actions';
import state from "./state";
import mutations from "./mutations";
import getters from "./getters";

export default {
  namespaced: true,
  getters,
  state,
  mutations,
  actions,
};
