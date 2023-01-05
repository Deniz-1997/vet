import { XHRLayout } from '@/core/utils/xhr';

/** User interface API. */
export class UserService {
  private xhr: XHRLayout = new XHRLayout();

  /**
   * Get info user.
   * @param {Data} data data.
   */
  public getInfo = (data) => {
    return this.xhr.post('/api/auth/userinfo', data);
  }
}
