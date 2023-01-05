import { LotService } from '@/api/lot/REST';

const Lot = new LotService();

export default {
  show(_, id) {
    return Lot.show(id);
  },

  getList(_, form) {
    return Lot.getList(form);
  },

  getListForElevator(_, form) {
    return Lot.getListForElevator(form);
  },

  create(_, form) {
    return Lot.create(form);
  },

  update(_, options) {
    const { id, data } = options;
    return Lot.update(id, data);
  },

  delete(_, options) {
    return Lot.delete(options.id);
  },

  debit(_, data) {
    return Lot.debit(data);
  },

  numbers(_, options) {
    const { data, type, id } = options;
    return Lot.numbers(data, type, id);
  },

  numbersGos(_, options) {
    const { data, type } = options;
    return Lot.numbersGos(data, type);
  },

  numbersGosGpb(_, options) {
    const { data, type } = options;
    return Lot.numbersGosGpb(data, type);
  },
  showGpb(_, id) {
    return Lot.showGpb(id);
  },

  getListGpb(_, form) {
    return Lot.getListGpb(form);
  },

  createGpb(_, form) {
    return Lot.createGpb(form);
  },

  updateGpb(_, options) {
    const { id, data } = options;
    return Lot.updateGpb(id, data);
  },

  deleteGpb(_, options) {
    return Lot.deleteGpb(options.id);
  },

  debitGpb(_, data) {
    return Lot.debitGpb(data);
  },

  createAmmend(_, form) {
    return Lot.createAmmend(form);
  },

  createAmmendGpb(_, form) {
    return Lot.createAmmendGpb(form);
  },
};
