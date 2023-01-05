import {PriorityAddressService} from "@/api/priorityAddress/REST";

const PriorityAddress = new PriorityAddressService();

export default {

  getList(_, form) {
    return PriorityAddress.getList(form);
  },

  showAddress(_, form) {
    return PriorityAddress.showAddress(form)
  },

  showCountry(_, form) {
    return PriorityAddress.showCountry(form)
  },

  updateContracts(_, form) {
      return PriorityAddress.updateContracts(form);
  },

  createAddress(_, form) {
    return PriorityAddress.createAddress(form);
  },
};
