import { FiasService } from "@/api/fias/REST";

const Fias = new FiasService();

export default {
  getChild(_, form) {
    return Fias.getChild(form);
  },
  findAddress(_, form) {
    return Fias.findAddress(form);
  },
  getAddress(_, form) {
    return Fias.getAddress(form);
  },
};
