import { XHRLayout } from '@/core/utils/xhr';
import { mapInnerForm } from './utils';

/** NSI interface API. */
export class StateAuthorityService {
  private xhr: XHRLayout = new XHRLayout();

  /**
    * Get list ogv.
    */
  public getList = (data) => {
    return this.xhr.post('/api/subject/ogv/find',
      { ...data.params });
  }

  /**
    * @param {Id} id data..
    */
  public getStateAuthorityById = (id: number) => {
    return this.xhr.get(`/api/subject/ogv/${id}`);
  }

  public createDivisionId = (data) => {
    return this.xhr.post('/api/subject/division/create', data);
  }

  public editDivision = (data) => {
    return this.xhr.post('/api/subject/division/update', data);
  }

  public deleteDivision = (data) => {
    return this.xhr.post('/api/subject/division/delete', data);
  }

  /**
   * Update ogv.
   */
  public updateStateAuthority = (data) => {
    return this.xhr.post('/api/subject/ogv/update', data);
  }

  /**
   * Create ogv.
   */
  public createStateAuthority = (data) => {
    return this.xhr.post('/api/subject/ogv/create', data);
  }

  /**
   * Get Hierarchy Divisions.
   */
  public getHierarchyDivisions = async (form: any) => {
    const { content } = await this.xhr.post('/api/subject/division/find', form);
    const data = mapInnerForm(content);
    return data;
  }

}