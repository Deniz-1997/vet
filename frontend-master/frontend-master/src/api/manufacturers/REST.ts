import { XHRLayout } from '@/core/utils/xhr';
import store from '@/store';
import axios from 'axios';
import get from 'lodash/get';
import { mapInnerForm } from './utils';

/** NSI interface API. */
export class ManufacturersService {
  private xhr: XHRLayout = new XHRLayout();

  /**
   * Get list manufacturers.
   */
  public getList = async (data) => {
    const response = await this.xhr.post(`/api/subject/manufacturer/manufacturers`, data);
    const content = response.content.map((item) => ({
      ...item,
      region: get(item, 'subject.oker.name_okato', ''),
    }));
    return {
      ...response,
      content,
    };
  };

  /**
   * Create manufacturers.
   */
  public createManufacturers = (data) => {
    const form = {
      ...data,
      processor: data.is_processor,
    };
    return this.xhr.post('/api/subject/manufacturer/create', form);
  };

  /**
   * Update manufacturers.
   */
  public updateManufacturers = (data) => {
    const form = {
      ...data,
      processor: data.is_processor,
    };
    return this.xhr.post('/api/subject/manufacturer/update', form);
  };

  /**
   * Get list request manufacturers.
   * @param {Id} id data.
   */
  public getListManufacturers = async (id) => {
    const res = await this.xhr.get(`/api/subject/manufacturer/${id}`);
    return mapInnerForm(res);
  };

  public getListManufacturersSubject = (id) => {
    return this.xhr.get(`/api/subject/manufacturer/subject/${id}`);
  };

  /**
   * Export manufacturers.
   */
  public exportManufacturers = (data) => {
    return this.xhr.post('/api/subject/manufacturer/exportReport', data);
  };
  /**
   * Exclude manufacturers.
   */
  public getReasonList = () => {
    return this.xhr.get('/api/subject/manufacturer/status_exclude_reasons');
  };

  /**
   * Exclude manufacturers.
   */
  public getRejectList = () => {
    return this.xhr.get('/api/subject/manufacturer/status_reject_reasons');
  };
  /**
   * Exclude manufacturers.
   * @param {Id} id data.
   * @param {reasonId} reasonId data.
   * @param {date} date data.
   */
  public excludeManufacturers = (data) => {
    return this.xhr.post(`/api/subject/manufacturer/exclude`, data);
  };

  /**
   * Export organization.
   */
  public exportReport = async () => {
    const response = await axios.get('/api/subject/manufacturer/exportReport', { responseType: 'blob' });

    const blob = new Blob([response.data], { type: 'application/vnd.ms-excel' });
    return store.commit('manufacturers/setExportDocumentData', URL.createObjectURL(blob));
  };
}
