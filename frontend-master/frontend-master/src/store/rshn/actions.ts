import { RshnService } from '@/api/rshn/REST';
import { publicApi } from '@/core/consts';

const Rshn = new RshnService();

export default {
  showWithdrawal(_, id) {
    return Rshn.showWithdrawal(id);
  },

  getListWithdrawal(_, form) {
    return Rshn.getListWithdrawal(form.data);
  },

  createWithdrawal(_, form) {
    return Rshn.createWithdrawal(form);
  },

  updateWithdrawal(_, form) {
    const { id, data } = form;
    return Rshn.updateWithdrawal(id, data);
  },

  deleteWithdrawal(_, form) {
    return Rshn.deleteWithdrawal(form.id);
  },

  createWithdrawalRestriction(_, form) {
    return Rshn.createWithdrawalRestriction(form);
  },

  deleteWithdrawalRestriction(_, form) {
    return Rshn.deleteWithdrawalRestriction(form.id);
  },

  updateWithdrawalRestriction(_, form) {
    const { id, data } = form;
    return Rshn.updateWithdrawalRestriction(id, data);
  },

  showPrescription(_, id) {
    return Rshn.showPrescription(id);
  },

  getListPrescription(_, form) {
    return Rshn.getListPrescription(form.data);
  },

  createPrescription(_, form) {
    return Rshn.createPrescription(form);
  },

  updatePrescription(_, form) {
    const { id, data } = form;
    return Rshn.updatePrescription(id, data);
  },

  deletePrescription(_, form) {
    return Rshn.deletePrescription(form.id);
  },

  createPrescriptionDoc(_, form) {
    return Rshn.createPrescriptionDoc(form);
  },

  deletePrescriptionDoc(_, form) {
    return Rshn.deletePrescriptionDoc(form.id);
  },

  cancelPrescriptionDoc(_, options) {
    return Rshn.cancelPrescriptionDoc(options.id);
  },

  updatePrescriptionDoc(_, form) {
    const { id, data } = form;
    return Rshn.updatePrescriptionDoc(id, data);
  },

  showExpertise(_, id) {
    return Rshn.showExpertise(id);
  },

  getListExpertise(_, form) {
    return Rshn.getListExpertise(form.data);
  },

  createExpertise(_, form) {
    return Rshn.createExpertise(form);
  },

  updateExpertise(_, form) {
    const { id, data } = form;
    return Rshn.updateExpertise(id, data);
  },

  deleteExpertise(_, form) {
    return Rshn.deleteExpertise(form.id);
  },

  findSdizByNumber(_, sdizNumber: string) {
    return Rshn.findSdizByNumber(sdizNumber);
  },

  findLotByNumber(_, lotNumber: string) {
    return Rshn.findLotByNumber(lotNumber);
  },
};
