import { StaffService } from '@/api/staff/REST';

const Staff = new StaffService();

export default {
  getStaffList(_, data) {
    return Staff.getStaffList(data);
  },
  updateStaff(_, form) {
    return Staff.updateStaff(form);
  },
  createStaff(_, form) {
    return Staff.createStaff(form);
  },
  showInfoStaff(_, data) {
    return Staff.showInfoStaff(data);
  },
  getStaffDivision(_, data) {
    return Staff.getStaffDivision(data);
  },
  changePassword(_, data) {
    return Staff.changePassword(data);
  },
  deactivation(_, data) {
     return Staff.deactivation(data);
  },
  activation(_, data) {
     return Staff.activation(data);
  },
};
