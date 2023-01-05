import { ManufacturersService } from '@/api/manufacturers/REST';

const Manufacturers = new ManufacturersService();

export default {
  getList(_, form) {
    return Manufacturers.getList(form);
  },
  getListManufacturers(_, id) {
    return Manufacturers.getListManufacturers(id);
  },
  getListManufacturersSubject(_, id) {
    return Manufacturers.getListManufacturersSubject(id);
  },
  updateManufacturers(_, form) {
    return Manufacturers.updateManufacturers(form);
  },
  createManufacturers(_, form) {
    return Manufacturers.createManufacturers(form);
  },
  exportManufacturers(_, form) {
    return Manufacturers.exportManufacturers(form);
  },
  getReasonList(_) {
    return Manufacturers.getReasonList();
  },
  getRejectList(_) {
    return Manufacturers.getRejectList();
  },
  excludeManufacturers(_, form) {
    return Manufacturers.excludeManufacturers(form);
  },
  exportReport(_, params) {
    return Manufacturers.exportManufacturers(params);
  },
};
