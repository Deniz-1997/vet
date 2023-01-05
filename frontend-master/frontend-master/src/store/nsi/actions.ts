import { NSIService } from '@/api/nsi/REST';

const NSI = new NSIService();

export default {
  getListProductType(_, data) {
    return NSI.getListTypeProduct(data);
  },
  getListProductTypeMsh(_, data) {
    return NSI.getListTypeProductMsh(data);
  },
  getList(_, params) {
    return NSI.getList(params);
  },

  getItem(_, form) {
    return NSI.getItem(form);
  },

  // getQualityIndicators(_, form) {
  //   return NSI.getQualityIndicators(form);
  // },

  create(_, form) {
    return NSI.create(form);
  },

  update(_, form) {
    return NSI.update(form);
  },

  search(_, form) {
    return NSI.search(form);
  },

  delete(_, id) {
    return NSI.delete(id);
  }
};
