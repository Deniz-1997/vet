import { ApprovalTaskService } from '@/api/approvalTasks/REST';

const approvalTask = new ApprovalTaskService();

export default {
  getListTask(_, form) {
    return approvalTask.getListTask(form);
  },
  getListStatuses(_) {
    return approvalTask.getListStatuses();
  },

  exportList(_, params) {
    return approvalTask.exportList(params);
  },

  fetchRejectList(_, typeId) {
    return approvalTask.fetchRejectList(typeId);
  },

  rejectTask(_, params) {
    return approvalTask.rejectTask(params);
  },

  approveTask(_, params) {
    return approvalTask.approveTask(params);
  },

  applyRequest(_, request_id: string | number) {
    return approvalTask.applyRequest(request_id);
  },

  getCountTasks(_) {
    return approvalTask.getCountTasks();
  },
};
