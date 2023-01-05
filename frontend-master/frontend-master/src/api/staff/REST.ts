import { XHRLayout } from '@/core/utils/xhr';

/** NSI interface API. */
export class StaffService {
  private xhr: XHRLayout = new XHRLayout();

  /**
   * Get staff list.
   */
  public getStaffList = (data) => {
    return this.xhr.post(`/api/security/user/find`, data);
  };

  /**
   * Get staff list.
   */
  public getStaffDivision = (data) => {
    return this.xhr.post('/api/security/user/ogv/find', data);
  };

  /**
   * Update information for the staff.
   */
  public updateStaff = (data) => {
    return this.xhr.post('/api/security/user/update', data);
  };

  /**
   * Create new staff.
   */
  public createStaff = (data) => {
    return this.xhr.post('/api/security/user/create', data);
  };

  /**
   * Show information for the staff.
   */
  public showInfoStaff = (data) => {
    return this.xhr.post('/api/security/user/show', data);
  };

  /**
   * Change password
   */
  public changePassword = (data) => {
    return this.xhr.post('/api/security/user/changePassword', data);
  };

  /**
   * Деактивация пользователя
   */
  public deactivation = (data) => {
    return this.xhr.post('/api/security/user/deactivation', data);
  };

  /**
   * Активация пользователя
   */
  public activation = (data) => {
    return this.xhr.post('/api/security/user/activation', data);
  };
}
