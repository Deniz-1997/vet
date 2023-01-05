import { XHRLayout } from '@/core/utils/xhr';

type Params = {
  pageable: {
    pageNumber: number
    pageSize: number
  }
}

/** NSI interface API. */
export class LoggingService {
  private xhr: XHRLayout = new XHRLayout();
  /**
    * Get list Logging.
    */
  public getList = (data: Params) => { 
    return this.xhr.post('/api/security/log/user/find', data);
  }

}