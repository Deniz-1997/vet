import { XHRLayout } from '@/core/utils/xhr';
import store from '@/store';
import axios from 'axios';

/** NSI interface API. */
export class LaboratoriesService {
  private xhr: XHRLayout = new XHRLayout();

  /**
   * Get list laboratory.
   */
  public getList = (data) => {
    return this.xhr.post('/api/laboratory/find', data);
  }

  /**
   * Get list request laboratory.
   * @param {Id} id data.
   */
  public getInfoLaboratory = (id) => {
    return this.xhr.post(`/api/laboratory/show`, id);
  }

  /**
    * Create laboratory.
    */
  public createLaboratory = (data) => {
    return this.xhr.post('/api/laboratory/include', {
      ...data,
      subject: {
        ...data.subject,
        subject_type: 'UL'
      }
    });
  }

  /**
    * Update laboratory.
    */
  public updateLaboratory = (data) => {
    return this.xhr.post('/api/laboratory/update', {
      ...data,
      subject: {
        ...data.subject,
        subject_type: 'UL'
      }
    });
  }

  /**
   * Exclude laboratory.
   * @param {Id} id data.
   * @param {reasonId} reasonId data.
   * @param {date} date data.
   */
  public excludeLaboratory = (data) => {
    return this.xhr.post(`/api/laboratory/exclude`, data);
  }



  /**
   * Exclude laboratory.
   * @param {Params} params data.
   */
  public exportReport = async (params) => {
    const response = await axios.get(`/api/laboratory/exportReport2`, { responseType: "blob" });
    return response;
  }

}
