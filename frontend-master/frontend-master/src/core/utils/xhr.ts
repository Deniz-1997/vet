import { AxiosInstance } from 'axios';

/**
 * @deprecated Use [services](src/services/index.ts) and context `$axios`.
 */
export class XHRLayout {
  static axios: AxiosInstance;

  public async get(url: string, params?: any) {
    const response = await XHRLayout.axios.get(url, { params });

    return response.data;
  }

  // eslint-disable-next-line max-params
  public async post(url: string, data?: any, headers?: Headers, params?: any, onUploadProgress?: any) {
    const response = await XHRLayout.axios.post(url, data, { headers, params, onUploadProgress });

    return response.data;
  }

  public async put(url: string, data?: any) {
    const response = await XHRLayout.axios.put(url, data);

    return response.data;
  }

  public async delete(url: string, params?: any) {
    const response = await XHRLayout.axios.delete(url, { params });

    return response.data;
  }
}
