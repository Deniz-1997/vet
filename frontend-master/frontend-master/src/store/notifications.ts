export default {
  namespaced: true,
  state: {
    count: 0,
  },
  mutations: {
    setCount(state, count) {
      state.count = count;
    },
  },
  getters: {
    count(state) {
      return state.count;
    },
  },
};
