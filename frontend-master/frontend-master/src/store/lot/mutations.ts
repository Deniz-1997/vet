export default {
  setDocumentTypes: (_, value) => {
    _.documentTypes = value;
  },
  setReturnReasons: (_, value) => {
    _.returnReasons = value;
  },
  setGpbCurrentLocation: (_, value) => {
    _.gpbCurrentLocation = value;
  },
  setLotCurrentLocation: (_, value) => {
    _.lotCurrentLocation = value;
  },
  clearLotCurrentLocation: (state) => {
    state.lotCurrentLocation = [];
  },
  clearGpbCurrentLocation: (state) => {
    state.gpbCurrentLocation = [];
  },
};
