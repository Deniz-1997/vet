export default {
  setQualityIndicators: (state, value) => {
    state.qualityIndicators = value;
  },
  setQualityIndicatorsLimit: (state, value) => {
    state.qualityIndicatorsLimit = value;
  },
  setProductTypesGrain: (state, value) => {
    state.productTypes.grain = value;
  },
  setProductTypesProduct: (state, value) => {
    state.productTypes.product = value;
  },
  setProductTypesPeriodProduct: (state, value) => {
    state.productTypes.periodProduct = value;
  },
  setProductTypesPeriodGrain: (state, value) => {
    state.productTypes.periodGrain = value;
  },
  setLotsTarget: (state, value) => {
    state.lotsTarget = value;
  },
  setLotsPurpose: (state, value) => {
    state.lotsPurpose = value;
  },
  setTransportTypes: (state, value) => {
    state.transportTypes = value;
  },
  setSampleTypes: (state, value) => {
    state.sampleTypes = value;
  },
  setProcessingMethods: (state, value) => {
    state.processingMethods = value;
  },
  setUnitsOfMeasure: (state, value) => {
    state.unitsOfMeasure = value;
  },
  setIndicatorPurposes: (state, value) => {
    state.indicatorPurposes = value;
  },
  setTnvedList: (state, value) => {
    state.tnvedList = value;
  },
  setOkpd2List: (state, value) => {
    state.okpd2List = value;
  },
  setOkpd2MshGrain: (state, value) => {
    state.okpd2MshList.grain = value;
  },
  setOkpd2MshProduct: (state, value) => {
    state.okpd2MshList.product = value;
  },
  setOkpd2MshPeriodProduct: (state, value) => {
    state.okpd2MshList.periodProduct = value;
  },
  setOkpd2MshPeriodGrain: (state, value) => {
    state.okpd2MshList.periodGrain = value;
  },
  setPropertyRights: (state, value) => {
    state.propertyRights = value;
  },
  setPropertyRightTransferDocTypes: (state, value) => {
    state.propertyRightTransferDocTypes = value;
  },
  setReasonWriteOffList: (state, value) => {
    state.reasonWriteOffList = value;
  },
  setStorageTypes: (state, value) => {
    state.storageTypes = value;
  },
  setWeightDisperancyCauses: (state, value) => {
    state.weightDisperancyCauses = value;
  },
};
