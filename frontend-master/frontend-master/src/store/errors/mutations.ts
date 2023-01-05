export default {
  init(state, service) {
    state.errors = service;
  },
  setErrorsList: ({ errors }, value) => {
    const list = Array.isArray(value) ? value : [value];

    list.forEach((text) => {
      errors.push('error', { text });
    });
  },
  clearErrorList: ({ errors }) => {
    errors.flush();
  },
};
