import { SdizService } from '@/api/sdiz/REST';

const Sdiz = new SdizService();

export default {
  show(_, id) {
    return Sdiz.show(id);
  },

  getList(_, form) {
    return Sdiz.getList(form);
  },

  findByNumber(_, form) {
    return Sdiz.findByNumber(form);
  },

  findByNumberGpb(_, form) {
    return Sdiz.findByNumberGpb(form);
  },
  getListForGpb(_, form) {
    return Sdiz.getListForGpb(form);
  },

  getListForElevator(_, form) {
    return Sdiz.getListForElevator(form);
  },

  getSdizTypes(_, form) {
    return Sdiz.getSdizTypes(form);
  },

  create(_, form) {
    return Sdiz.create(form);
  },

  createOrganizaton(_, form) {
    return Sdiz.createOrganizaton(form);
  },

  getOPF(_, form) {
    return Sdiz.getOPF(form);
  },

  extinguish(_, form) {
    return Sdiz.extinguish(form);
  },

  update(_, options) {
    const { id, data } = options;
    return Sdiz.update(id, data);
  },

  delete(_, options) {
    const { id } = options;
    return Sdiz.delete(id);
  },

  numbers(_, options) {
    const { data, type } = options;
    return Sdiz.numbers(data, type);
  },

  createForGpb(_, form) {
    return Sdiz.createForGpb(form);
  },

  showForGpb(_, id) {
    return Sdiz.showForGpb(id);
  },

  updateForGpb(_, options) {
    const { id, data } = options;
    return Sdiz.updateForGpb(id, data);
  },

  extinguishGpb(_, options) {
    return Sdiz.extinguishGpb(options);
  },

  deleteForGpb(_, options) {
    const { id } = options;
    return Sdiz.deleteForGpb(id);
  },

  extinguishCancel(_, id) {
    return Sdiz.extinguishCancel(id);
  },
  extinguishCancelGpb(_, id) {
    return Sdiz.extinguishCancelGpb(id);
  },

  getListAgent(_, form) {
    return Sdiz.getListAgent(form);
  },

  createAgent(_, form) {
    return Sdiz.createAgent(form);
  },

  showAgent(_, id) {
    return Sdiz.showAgent(id);
  },

  updateAgent(_, options) {
    const { id, data } = options;
    return Sdiz.updateAgent(id, data);
  },

  deleteAgent(_, options) {
    const { id } = options;
    return Sdiz.deleteAgent(id);
  },

  findForAgent(_, form) {
    return Sdiz.findForAgent(form);
  },

  extinguishRefusal(_, form) {
    return Sdiz.extinguishRefusal(form);
  },

  extinguishRefusalGpb(_, form) {
    return Sdiz.extinguishRefusalGpb(form);
  },

  extinguishRefusalCancel(_, id) {
    return Sdiz.extinguishRefusalCancel(id);
  },

  extinguishRefusalCancelGpb(_, id) {
    return Sdiz.extinguishRefusalCancelGpb(id);
  },

  confirm(_, id) {
    return Sdiz.confirm(id);
  },

  confirmGpb(_, id) {
    return Sdiz.confirmGpb(id);
  },

  showLot(_, id) {
    return Sdiz.showLot(id);
  },

  showLotGpb(_, id) {
    return Sdiz.showLotGpb(id);
  },
};
