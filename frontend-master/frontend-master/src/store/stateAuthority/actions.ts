import { StateAuthorityService } from '@/api/stateAuthority/REST';

const StateAuthority = new StateAuthorityService();

export default {
  getList(_, form) {
    return StateAuthority.getList(form);
  },
  updateStateAuthority(_, form) {
    return StateAuthority.updateStateAuthority(form);
  },
  createStateAuthority(_, form) {
    return StateAuthority.createStateAuthority(form);
  },
  getStateAuthorityById(_, id) {
    return StateAuthority.getStateAuthorityById(id);
  },
  createDivisionId(_, form){
    return StateAuthority.createDivisionId(form);
  },
  editDivision(_, form){
    return StateAuthority.editDivision(form);
  },
  deleteDivision(_, form){
    return StateAuthority.deleteDivision(form);
  },
  getHierarchyDivisions(_, form){
    return StateAuthority.getHierarchyDivisions(form);
  }
};
