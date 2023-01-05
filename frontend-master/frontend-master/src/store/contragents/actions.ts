import {ContragentsService} from "@/api/contragents/REST";

const Contragents = new ContragentsService();

export default {
  getList(_) {
    return Contragents.getList();
  },
  getListAddress(_) {
    return Contragents.getListAddress()
  },
};
