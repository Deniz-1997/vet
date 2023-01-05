export default {
  setFilters: (state, data: { name_route_list: string; filters: any }) => {
    if (!data.name_route_list || typeof state[data.name_route_list] === 'undefined') {
      return;
    }

    state[data.name_route_list] = data.filters;
  },
  clearFilters: (state, name_route_list) => {
    if (!name_route_list || typeof state[name_route_list] === 'undefined') {
      return;
    }

    state[name_route_list] = {};
  },
  clearAllFilters: (state) => {
    Object.keys(state).forEach((key) => {
      state[key] = {};
    });
  },
};
