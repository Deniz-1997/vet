import { XHRLayout } from '@/core/utils/xhr';

/** NSI interface API. */
export class UserRolesService {
  private xhr: XHRLayout = new XHRLayout();

  getUserRolesList = (data) => {
    return this.xhr.post(`/api/security/role`, data);
  };

  /**
   * Показать информацию по роли
   */
  public showInfoRole = (id) => {
    return this.xhr.get(`/api/security/role/${id}`);
  };

  /**
   * Назначить роль
   */
  public assignRole = (data) => {
    return this.xhr.post('/api/security/role/assign', data);
  };

  /**
   * Получить список ролей для пользователя
   */
  public getForAssign = (data) => {
    return this.xhr.post('/api/security/role/get_for_assign', data);
  };
}
