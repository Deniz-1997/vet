export default {
  changeAddress: (_, value) => {
    _.addressObjects = value;
  },
  cacheAddress: (_, value) => {
    _.addressObjectsCache = value;
  },
}
