import { GosmonitoringService } from '@/api/gosmonitoring/REST';

const Gosmonitoring = new GosmonitoringService();

export default {
  getList(_, options) {
    const { url, data } = options;
    return Gosmonitoring.getList(url, data);
  },

  create(_, options) {
    const { url, data } = options;
    return Gosmonitoring.create(url, data);
  },

  update(_, options) {
    const { id, data } = options;
    return Gosmonitoring.update(id, data.data, data.url);
  },

  delete(_, options) {
    const { url, id } = options;
    return Gosmonitoring.delete(url, id);
  },

  createAmmend(_, form) {
    return Gosmonitoring.createAmmend(form);
  },
};
