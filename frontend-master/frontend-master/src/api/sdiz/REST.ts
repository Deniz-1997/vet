import { XHRLayout } from '@/core/utils/xhr';
import { publicApi } from '@/core/consts';

export class SdizService {
  private xhr: XHRLayout = new XHRLayout();

  public show = (id) => {
    return this.xhr.get(`/api/sdiz/show/${id}`);
  };

  public getList = (data) => {
    return this.xhr.post(`/api/sdiz/list`, data);
  };

  public getListForce = (data) => {
    return this.xhr.post(`/api/sdiz/listForce`, data);
  };

  public getListForElevator = (data) => {
    return this.xhr.post('/api/sdiz/find/for-elevator', data);
  };
  public getStatus = () => {
    return this.xhr.get(`/api/sdiz/statuses`);
  };

  /**
   * Create a sdiz.
   * @param {Data} data.
   */
  public create = (data) => {
    return this.xhr.post('/api/sdiz/create', data);
  };
  /**
   * Create an organization
   * @param {Data} data.
   */
  public createOrganizaton = (data) => {
    return this.xhr.post('/api/subject/create', data);
  };
  /**
   * Create an organization
   * @param {Data} data.
   */
  public getOPF = (data) => {
    return this.xhr.get('/api/subject/opf', data);
  };

  public getSdizTypes = (data) => {
    return this.xhr.get('/api/sdiz/types', data);
  };

  /**
   * Extinguish sdiz
   */
  public extinguish = (data) => {
    return this.xhr.post('/api/sdiz/extinguish', data);
  };

  /**
   * Edit a nsi.
   * @param {data} data.
   * @param id
   */
  public update = (id, data) => {
    return this.xhr.put(`${publicApi}sdiz/?id=${id}`, data);
  };

  /**
   * Delete a nsi.
   * @param id
   */
  public delete = (id) => {
    return this.xhr.delete(`${publicApi}sdiz/?id=${id}`);
  };

  public numbers = (data, type) => {
    switch (type) {
      case 'update':
        // return this.xhr.put(`${publicApi}sdiz/numbers`, data);
        break;

      case 'create':
        return this.xhr.post(`${publicApi}sdiz/numbers/create/sdiz/gos`, data);

      case 'delete':
        // return this.xhr.delete(`${publicApi}sdiz/numbers?id=${id}`);
        break;

      default:
        return this.xhr.post(`${publicApi}sdiz/numbers/find/sdiz/gos`, data);
    }
  };

  public getListForGpb = (data) => {
    return this.xhr.post('/api/sdiz/gpb/list', data);
  };

  public findByNumber = (data) => {
    return this.xhr.get(`/api/sdiz/find/by/number?number=${data}`);
  };

  public findByNumberGpb = (data) => {
    return this.xhr.get(`/api/sdiz/gpb/find/by/number?number=${data}`);
  };

  public createForGpb = (data) => {
    return this.xhr.post(`/api/sdiz/gpb/create`, data);
  };

  public showForGpb = (id) => {
    return this.xhr.get(`/api/sdiz/gpb/show/${id}`);
  };

  public updateForGpb = (id, data) => {
    return this.xhr.put(`/api/sdiz/gpb/${id}`, data);
  };

  public deleteForGpb = (id) => {
    return this.xhr.delete(`/api/sdiz/gpb/${id}`);
  };

  public extinguishGpb = (data) => {
    return this.xhr.post('/api/sdiz/gpb/extinguish', data);
  };

  public getListAgent = (data) => {
    return this.xhr.post('/api/sdiz/agent/list', data);
  };

  public createAgent = (data) => {
    return this.xhr.post(`/api/sdiz/agent/create`, data);
  };

  public showAgent = (id) => {
    return this.xhr.get(`/api/sdiz/agent/show/${id}`);
  };

  public updateAgent = (id, data) => {
    return this.xhr.put(`/api/sdiz/agent/${id}`, data);
  };

  public deleteAgent = (id) => {
    return this.xhr.delete(`/api/sdiz/agent/${id}`);
  };

  public findForAgent = (data) => {
    return this.xhr.post(`/api/sdiz/agent/find/sdiz`, data);
  };

  public extinguishCancel = (id) => {
    return this.xhr.get(`/api/sdiz/extinguish/cancel/${id}`);
  };

  public extinguishCancelGpb = (id) => {
    return this.xhr.get(`/api/sdiz/gpb/extinguish/cancel/${id}`);
  };

  public extinguishRefusal = (data) => {
    return this.xhr.post('/api/sdiz/extinguish/refusal/', data);
  };

  public extinguishRefusalGpb = (data) => {
    return this.xhr.post('/api/sdiz/gpb/extinguish/refusal/', data);
  };

  public extinguishRefusalCancel = (id) => {
    return this.xhr.get(`/api/sdiz/extinguish/refusal/cancel/${id}`);
  };

  public extinguishRefusalCancelGpb = (id) => {
    return this.xhr.get(`/api/sdiz/gpb/extinguish/refusal/cancel/${id}`);
  };

  public confirm = (id) => {
    return this.xhr.post(`/api/sdiz/confirm/${id}`);
  };

  public confirmGpb = (id) => {
    return this.xhr.post(`/api/sdiz/gpb/confirm/${id}`);
  };

  public showLot = (id) => {
    return this.xhr.get(`/api/sdiz/show/lot/${id}`);
  };

  public showLotGpb = (id) => {
    return this.xhr.get(`/api/sdiz/gpb/show/gpb/${id}`);
  };
}
