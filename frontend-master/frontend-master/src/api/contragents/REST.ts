import { XHRLayout } from '@/core/utils/xhr';

/** NSI interface API. */
export class ContragentsService {
  private xhr: XHRLayout = new XHRLayout();
  /**
    * Get list contragent.
    */
  public getList = () => {
    return this.xhr.post('/api/directory/contragent/find', {});
  }
  /**
    * Get list contragent.
    */
  public getListAddress = () => {
    return this.xhr.post('/api/directory/address/find', {});
  }
}