import { UserRolesService } from '@/api/userRoles/REST';

const UserRoles = new UserRolesService();

export default {
  getUserRolesList(_, data) {
    return UserRoles.getUserRolesList(data);
  },
  showInfoRole(_, data) {
    return UserRoles.showInfoRole(data);
  },

  assignRole(_, data) {
    return UserRoles.assignRole(data);
  },

  getForAssign(_, data) {
    return UserRoles.getForAssign(data);
  },
};
