import {LaboratoriesService} from "@/api/labolatories/REST";

const Laboratories = new LaboratoriesService();

export default {
  getList(_, form) {
      return Laboratories.getList(form);
  },
  getInfoLaboratory(_, id) {
    return Laboratories.getInfoLaboratory(id)
  },
  updateLaboratories(_, form) {
    return Laboratories.updateLaboratory(form);
  },
  createLaboratories(_, form) {
    return Laboratories.createLaboratory(form);
  },
  excludeLaboratories(_, form) {
    return Laboratories.excludeLaboratory(form)
  },
  exportReport(_, params) {
    return Laboratories.exportReport(params);
  }
};
