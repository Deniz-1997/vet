import { XHRLayout } from '@/core/utils/xhr';
import { publicApi } from '@/core/consts';

export class GpboService {
  private xhr: XHRLayout = new XHRLayout();

  public createGpbOut = (data) => {
    return this.xhr.post(`${publicApi}gpbo/create/`, data.data);
  };

  public showGpbOut = (id) => {
    return this.xhr.get(`${publicApi}gpbo/show/${id}`);
  };

  public getListGpbOut = (data) => {
    return this.xhr.post(`${publicApi}gpbo/list`, data);
  };

  public updateGpbOut = (id, data) => {
    return this.xhr.put(`${publicApi}gpbo/?id=${id}`, data);
  };

  public deleteGpbOut = (id) => {
    return this.xhr.delete(`${publicApi}gpbo/?id=${id}`);
  };
}
