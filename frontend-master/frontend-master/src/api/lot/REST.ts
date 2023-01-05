import { XHRLayout } from '@/core/utils/xhr';
import { publicApi } from '@/core/consts';

export class LotService {
  private xhr: XHRLayout = new XHRLayout();

  public show = (id) => {
    return this.xhr.get(`/api/lot/show/${id}`);
  };

  public getList = (data) => {
    return this.xhr.post('/api/lot/list', data);
  };

  public getListForElevator = (data) => {
    return this.xhr.post('/api/lot/find/for-elevator', data);
  };

  public update = (id, data) => {
    return this.xhr.put(`${publicApi}lot/?id=${id}`, data);
  };

  public delete = (id) => {
    return this.xhr.delete(`${publicApi}lot/?id=${id}`);
  };

  public debit = (data) => {
    return this.xhr.post(`${publicApi}lot/debit`, data);
  };

  public create = (data) => {
    return this.xhr.post(`${publicApi}lot/create/from/${data.type}`, data.data);
  };

  public numbersGos = (data, type) => {
    switch (type) {
      case 'create':
        return this.xhr.post(`${publicApi}lot/numbers/create/gos`, data);
      default:
        return this.xhr.post(`${publicApi}lot/numbers/find/gos`, data);
    }
  };

  public numbersGosGpb = (data, type) => {
    switch (type) {
      case 'create':
        return this.xhr.post(`${publicApi}lot/numbers/create/gpb/gos`, data);
      default:
        return this.xhr.post(`${publicApi}lot/numbers/find/gpb/gos`, data);
    }
  };

  public numbers = (data, type, id) => {
    switch (type) {
      case 'update':
        return this.xhr.put(`${publicApi}lot/numbers?id=${id}`, data);

      case 'create':
        return this.xhr.post(`${publicApi}lot/numbers/create`, data);

      case 'delete':
        return this.xhr.delete(`${publicApi}lot/numbers?id=${id}`);

      default:
        return this.xhr.post(`${publicApi}lot/numbers`, data);
    }
  };
  public showGpb = (id) => {
    return this.xhr.get(`/api/gpb/show/${id}`);
  };

  public getListGpb = (data) => {
    return this.xhr.post('/api/gpb/list', data);
  };

  public updateGpb = (id, data) => {
    return this.xhr.put(`/api/gpb/?id=${id}`, data);
  };

  public deleteGpb = (id) => {
    return this.xhr.delete(`/api/gpb/?id=${id}`);
  };

  public debitGpb = (data) => {
    return this.xhr.post('/api/gpb/debit', data);
  };

  public createGpb = (data) => {
    return this.xhr.post(`/api/gpb/create/from/${data.type}`, data.data);
  };

  public createAmmend = (data) => {
    return this.xhr.post(`${publicApi}lot/change`, data);
  };

  public createAmmendGpb = (data) => {
    return this.xhr.post(`${publicApi}gpb/change`, data);
  };
}
