import { XHRLayout } from '@/core/utils/xhr';

/** Approval task interface API. */
export class ApprovalTaskService {
  private xhr: XHRLayout = new XHRLayout();

  /**
   * Get list approval tasks.
   */
  public getListTask = (data) => {
    return this.xhr.post('/api/approval-task/tasks', data);
  };

  /**
   * Get list approval statuses.
   */
  public getListStatuses = () => {
    return this.xhr.get('/api/approval-task/statuses');
  };

  /**
   * Export list.
   */
  public exportList = (params) => {
    return this.xhr.get('/api/approval-task/exportReport', params);
  };

  /**
   * Get list reject item.
   * @param {typeId} typeId .
   */
  public fetchRejectList = (request_type_id: number) => {
    return this.xhr.post('/api/nci/registryStatementRejectReason', { request_type: { id: request_type_id } });
  };

  /**
   * Rejected select organization.
   * @param {data} data params for the approve task.
   */
  public rejectTask = (data) => {
    return this.xhr.post('/api/approval-task/reject', data);
  };

  /**
   * Send requests to approve
   * @param {id} id id requests
   */
  public applyRequest = (id) => {
    return this.xhr.post('/api/approval-task/apply', { request_id: id });
  };

  /**
   * Approve select application.
   * @param {data} data params for the approve task.
   */
  public approveTask = (data) => {
    return this.xhr.post('/api/approval-task/approve', data);
  };

  /**
   * Получить количество задач на рассмотрении
   */
  public getCountTasks = () => {
    return this.xhr.get('/api/approval-task/countTasks');
  };
}
