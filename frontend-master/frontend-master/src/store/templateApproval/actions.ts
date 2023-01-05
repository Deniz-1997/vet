import { TemplateApprovalService } from '@/api/templateApproval/REST';

const TemplateApproval = new TemplateApprovalService();

export default {
  getList(_, form) {
    return TemplateApproval.getListTemplate(form);
  },
  getInfoTemplate(_, id) {
    return TemplateApproval.getInfoAboutTemplate(id);
  },
  removeTemplate(_, id) {
    return TemplateApproval.removeTemplate(id);
  },
  getListTypes(_) {
    return TemplateApproval.getListTemplateTypes();
  },
  getListApprovalOrganization(_, params?) {
    return TemplateApproval.getListApprovalOrganization(params);
  },
  getListResponsible(_, id) {
    return TemplateApproval.getListResponsible(id);
  },
  createTemplate(_, data) {
    return TemplateApproval.createTemplate(data);
  },
  getListDivisions(_, data){
    return TemplateApproval.getListDivisions(data);
  },
  activetedTemplate(_, data) {
    return TemplateApproval.activatedTemplate(data);
  },
  exportReport(_, data) {
    return TemplateApproval.exportReport(data);
  },
  getListAutomaticStages(_) {
    return TemplateApproval.getListAutomaticStages();
  }
};
