import { XHRLayout } from '@/core/utils/xhr';
import { publicApi } from '@/core/consts';

/** Gosmonitoring interface API. */
export class GosmonitoringService {
  private xhr: XHRLayout = new XHRLayout();

  /**
   * Get Gosmonitoring
   * @param {data} data
   * @param {url} url
   */
  public getList = (url, data) => {
    return this.xhr.post(`${publicApi}gosmonitoring/${url}`, data);
  };

  /**
   * Create a Gosmonitoring
   * @param {data} data
   * @param {url} url
   */
  public create = (url, data) => {
    return this.xhr.post(`${publicApi}gosmonitoring/${url}/create`, data);
  };
  /**
   * Edit a Gosmonitoring
   * @param {id} id
   * @param {data} data
   * @param {url} url
   */
  public update = (id, data, url) => {
    return this.xhr.put(`${publicApi}gosmonitoring/${url}/?id=${id}`, data);
  };
  /**
   * Delete a Gosmonitoring.
   * @param {url} url
   * @param {id} id
   */
  public delete = (url, id) => {
    return this.xhr.delete(`${publicApi}gosmonitoring/${url}/?id=${id}`);
  };

  public createAmmend = (data) => {
    return this.xhr.post(`${publicApi}gosmonitoring/register/research-register/change`, data);
  };
}
