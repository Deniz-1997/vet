import { OrganizationService } from '@/api/organization/REST';

const Organization = new OrganizationService();

export default {
  getList(_, form) {
    return Organization.getList(form);
  },
  getListRequests(_, id) {
    return Organization.getListRequests(id);
  },
  getInfoOrganization(_, id) {
    return Organization.getInfoOrganization(id);
  },
  getActualInfo(_, id) {
    return Organization.getActualInfo(id);
  },
  organizationField(_, id) {
    return Organization.organizationField(id);
  },
  searchOrganization(_, query) {
    return Organization.searchOrganization(query);
  },
  getElevatorSubject(_, data) {
    return Organization.getElevatorSubject(data);
  },
  getSubjectDocument(_) {
    return Organization.getSubjectDocument();
  },
  getactualManufacturers(_, data) {
    return Organization.getactualManufacturers(data);
  },
  changeEnabled(_, id: string) {
    return Organization.changeEnable(id);
  },
  exportReport(_) {
    return Organization.exportReport();
  },
};
