import { GpboService } from '@/api/gpbo/REST';

const Gpbo = new GpboService();

export default {
  showGpbOut(_, id) {
    return Gpbo.showGpbOut(id);
  },

  getListGpbOut(_, form) {
    return Gpbo.getListGpbOut(form.data);
  },

  createGpbOut(_, form) {
    return Gpbo.createGpbOut(form);
  },

  updateGpbOut(_, form) {
    const {id, data} = form;
    return Gpbo.updateGpbOut(id, data);
  },

  deleteGpbOut(_, form) {
    return Gpbo.deleteGpbOut(form.id);
  },
};
