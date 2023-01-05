import { IAddressItem } from '@/services/models/common';
import { XHRLayout } from '@/core/utils/xhr';
import axios from 'axios';

export class FiasService {
  static cache = new Map<string, IAddressItem[]>();
  private cancel;
  private xhr: XHRLayout = new XHRLayout();

  /**
   * Get address list.
   * @param {Data} data.
   */
  public getChild = (data) => {
    return this.xhr.post('/api/fias/findAllForAddressObject', data);
  };

  /**
   * find address.
   * @param {Data} data.
   */
  public findAddress = async (data) => {
    const cacheKey = `${data.countryId}@${data.address}@${data.div_type}@${data.ao_guid}`;

    if (FiasService.cache.has(cacheKey)) {
      return FiasService.cache.get(cacheKey);
    }

    if (this.cancel) {
      this.cancel();
    }

    const { token, cancel } = axios.CancelToken.source();
    this.cancel = cancel;

    try {
      const response = await XHRLayout.axios.post('/api/fias/' + (data.ao_guid != null ? 'findAddressObject' : 'findAddress'), data, { cancelToken: token });
      this.cancel = null;
      FiasService.cache.set(cacheKey, response.data);
      return response.data;
    } catch (error) {
      console.error(error);
      return null;
    }
  };

  /**
   * get address.
   * @param {Data} data.
   */
  public getAddress = (data) => {
    return this.xhr.post('/api/fias/getAddress', data);
  };
}
