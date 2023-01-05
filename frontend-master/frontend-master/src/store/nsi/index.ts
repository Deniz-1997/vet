import actions from './actions';
import getters from "@/store/nsi/getters";
import state from "@/store/nsi/state";
import mutations from "@/store/nsi/mutations";

export default {
  namespaced: true,
  getters,
  state,
  mutations,
  actions,
};
