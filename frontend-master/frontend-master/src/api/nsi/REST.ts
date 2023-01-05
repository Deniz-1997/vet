import { XHRLayout } from '@/core/utils/xhr';
import { mapForm, mapInnerForm } from './utils';

/** NSI interface API. */
export class NSIService {
  private xhr: XHRLayout = new XHRLayout();

  /**
   * Get list type produce.
   */
  public getListTypeProduct = (data) => {
    return this.xhr.post('/api/nci/typeProduct', { ...data });
  };
  public getListTypeProductMsh = (data) => {
    return this.xhr.post('/api/nci/typeProduct/msh', { ...data });
  };

  /**
   * Get organizations.
   * @param {Params} params data.
   */
  public getList = async (params) => {
    const response = await this.xhr.post(params.url, params.params ? params.params : params.data);
    const data = mapInnerForm(response);
    return data;
  };

  /**
   * Get organizations.
   * @param {Data} data data.
   * @param {url} url.
   */
  public getItem = async ({ url, data }) => {
    const response = await this.xhr.post(`${url}/show`, data);
    const elem = mapForm(response);
    return elem;
  };

  // /**
  //  * Get organizations.
  //  * @param {Data} data data.
  //  * @param {url} url.
  //  */
  // public getQualityIndicators = ({url, data}) => {
  //   return this.xhr.post(`${url}show/details`, data);
  // }

  /**
   * Create a nsi.
   * @param {Data} data.
   * @param {url} url.
   */
  public create = ({ url, data, ignoreStatuses }) => {
    return XHRLayout.axios.post(`${url}/create`, data, { ignoreStatuses });
  };

  /**
   * Edit a nsi.
   * @param {Data} data.
   * @param {url} url.
   */
  public update = ({ url, data }) => {
    return this.xhr.post(`${url}/update`, data);
  };
  /**
   * Delete a nsi.
   * @param {url} url.
   * @param {Data} data.
   */
  public delete = ({ url, data }) => {
    return this.xhr.post(`${url}/delete`, data);
  };
  /**
   * Delete a nsi.
   * @param {url} url.
   * @param {Data} data.
   */
  public search = ({ url, data }) => {
    return this.xhr.post(`${url}`, data);
  };
}
