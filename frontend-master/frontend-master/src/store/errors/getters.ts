export default {
  getErrorsList({ errors }) {
    return errors.find('error').map(({ text }) => text);
  },
};
