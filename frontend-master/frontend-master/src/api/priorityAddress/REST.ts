import { XHRLayout } from '@/core/utils/xhr';

export class PriorityAddressService {
  private xhr: XHRLayout = new XHRLayout();

  public getList = (data) => {
    return this.xhr.post('/api/priority/address', data);
  };

  public showAddress = (data) => {
    return this.xhr.post(`/api/priority/address/show`, data);
  };

  public showCountry = (data) => {
    return this.xhr.post(`/api/priority/address/country`, data);
  };

  public updateContracts = (data) => {
    return this.xhr.post('/api/priority/address/update', data);
  };

  public createAddress = (data) => {
    return this.xhr.post('/api/priority/address/create', data);
  };

  public deleteContracts = (data) => {
    return this.xhr.post('/api/priority/address/delete', data);
  };
}
