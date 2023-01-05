import { ContractsService } from "@/api/contracts/REST";

const Contracts = new ContractsService();

export default {

  getList(_, form) {
    return Contracts.getList(form);
  },

  showInfo(_, id) {
    return Contracts.showContract(id)
  },

  updateContracts(_, form) {
    return Contracts.updateContracts(form);
  },

  createContracts(_, form) {
    return Contracts.createContracts(form);
  },

  exportContracts(_, form) {
    return Contracts.exportContracts(form)
  },

  deleteContract(_, form) {
    return Contracts.deleteContracts(form)
  }
};