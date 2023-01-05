import { UserService } from '@/api/user/REST';

const User = new UserService();

export default {
  getInfo(_, form) {
    return User.getInfo(form);
  }
};
