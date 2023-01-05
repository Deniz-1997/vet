export default {
  getQualityIndicators(state) {
    return state.qualityIndicators;
  },
  getQualityIndicatorsLimit(state) {
    return state.qualityIndicatorsLimit;
  },
  getProductTypesGrain(state) {
    return state.productTypes.grain;
  },
  getProductTypesProduct(state) {
    return state.productTypes.product;
  },
  getProductTypesPeriodProduct(state) {
    return state.productTypes.periodProduct;
  },
  getProductTypesPeriodGrain(state) {
    return state.productTypes.periodGrain;
  },
  getLotsTarget(state) {
    return state.lotsTarget;
  },
  getLotsPurpose(state) {
    return state.lotsPurpose;
  },
  getSampleTypes(state) {
    return state.sampleTypes;
  },
  getTransportTypes(state) {
    return state.transportTypes;
  },
  getProcessingMethods(state) {
    return state.processingMethods;
  },
  getUnitsOfMeasure(state) {
    return state.unitsOfMeasure;
  },
  getIndicatorPurposes(state) {
    return state.indicatorPurposes;
  },
  getOkpd2List(state) {
    return state.okpd2List;
  },
  getTnvedList(state) {
    return state.tnvedList;
  },
  getOkpd2MshGrain(state) {
    return state.okpd2MshList.grain;
  },
  getOkpd2MshProduct(state) {
    return state.okpd2MshList.product;
  },
  getOkpd2MshPeriodProduct(state) {
    return state.okpd2MshList.periodProduct;
  },
  getOkpd2MshPeriodGrain(state) {
    return state.okpd2MshList.periodGrain;
  },
  getPropertyRights(state) {
    return state.propertyRights;
  },
  getPropertyRightTransferDocTypes(state) {
    return state.propertyRightTransferDocTypes;
  },
  getReasonWriteOffList(state) {
    return state.reasonWriteOffList;
  },
  getStorageTypes(state) {
    return state.storageTypes;
  },
  getWeightDisperancyCauses(state) {
    return state.weightDisperancyCauses;
  },
};
