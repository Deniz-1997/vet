import { XHRLayout } from '@/core/utils/xhr';
import { publicApi } from '@/core/consts';

/** Auth interface API. */
export class AuthService {
  private xhr: XHRLayout = new XHRLayout();

  /**
   * Get login token.
   * @param {User} user User data.
   */
  public getToken = (user) => {
    return this.xhr.post('/api/auth/login', user);
  }

  /**
   * Check user for the agreement
   */
  public getCheckForAgreement = (params) => {
    return this.xhr.post('/api/auth/personal_data_confirmation', {...params})
  }

  /**
   * Get esia token.
   * @param {User} user User data.
   */
   public getEsiaLogin = (user) => {
    return this.xhr.post('api/esia/callback', user)
  }

  /**
   * Get link for the authorization through the ESIA.
   */
  public getLinkAuth = () => {
    return this.xhr.get('api/esia/authorize');
  }

  /**
   * Log out user.
   */
  public logout = () => {
    return this.xhr.get('/api/auth/logout');
  }
}
