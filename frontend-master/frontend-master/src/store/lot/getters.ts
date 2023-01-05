export default {
  getDocumentTypes(state) {
    return state.documentTypes;
  },
  getReturnReasons(state) {
    return state.returnReasons;
  },
  getGpbCurrentLocation: (state) => {
    return state.gpbCurrentLocation;
  },
  getLotCurrentLocation: (state) => {
    return state.lotCurrentLocation;
  },
};
