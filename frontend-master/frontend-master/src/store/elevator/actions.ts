import { ElevatorsService } from '@/api/elevator/REST';

const Elevators = new ElevatorsService();

export default {
  getInfoElevator(_, id) {
    return Elevators.getElevatorInfo(id);
  },
  getActualInfoElevator(_, id) {
    return Elevators.getActualInfoElevator(id);
  },
  getInfoElevatorForCardOrganization(_, id) {
    return Elevators.getInfoElevatorForCardOrganization(id);
  },
  getListRequests(_, data) {
    return Elevators.getListRequests(data);
  },
  getListInsuranceDocument(_) {
    return Elevators.getListInsuranceDocument();
  },
  getListUsageRights(_) {
    return Elevators.getListUsageRights();
  },
  getListGranaryType(_, data) {
    return Elevators.getListGranaryType(data);
  },
  getListProccessingMethod(_, data) {
    return Elevators.getListProccessingMethod(data);
  },
  getListServiceType(_) {
    return Elevators.getListServiceType();
  },
  create(_, data) {
    return Elevators.create(data);
  },
  update(_, data) {
    return Elevators.update(data);
  },
  deleteDraft(_, id) {
    return Elevators.delete(id);
  },
  sendApproval(_, id) {
    return Elevators.sendApproval(id);
  },
  getRequestType(_, id) {
    return Elevators.getRequestType(id);
  },
  fetchReasonList(_) {
    return Elevators.reasonList();
  },
  getStorageMethodList(_, data) {
    return Elevators.getListStorageMethod(data);
  },
  getChangesRequest(_, id) {
    return Elevators.getChangesRequest(id);
  },
  getRequestChangesList(_, id) {
    return Elevators.getRequestChangesList(id);
  },
  excludeOrganization(_, params) {
    return Elevators.excludeOrganization(params);
  },
  getListRailwayStation(_, data) {
    return Elevators.getListRailwayStation(data);
  },
  exportReport(_) {
    return Elevators.exportReport();
  },
  getStatement(_, params) {
    return Elevators.getStatement(params);
  },
};
