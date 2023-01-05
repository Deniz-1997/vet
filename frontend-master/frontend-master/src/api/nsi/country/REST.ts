import { XHRLayout } from '@/core/utils/xhr';
import memoize from 'lodash/memoize';

/** Country interface API. */
export class CountryService {
  private xhr: XHRLayout = new XHRLayout();

  /**
   * Get list country.
   * @param {DataRegisterType} data data.
   */
  public getList = memoize(() => {
    return this.xhr.post('/api/nci/country');
  });
}
