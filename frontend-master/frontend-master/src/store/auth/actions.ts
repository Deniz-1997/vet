import { AuthService } from '@/api/auth/REST';

const Auth = new AuthService();

export default {
  getToken(_, form) {
    return Auth.getToken(form);
  },
  getCheckAgreement(_, params) {
    return Auth.getCheckForAgreement(params);
  },
  getEsiaLogin(_, form) {
    return Auth.getEsiaLogin(form);
  },
  getLinkEsia(_) {
    return Auth.getLinkAuth();
  },
  logout(_) {
    return Auth.logout();
  },
};
