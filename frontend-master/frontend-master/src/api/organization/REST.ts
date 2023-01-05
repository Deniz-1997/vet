import { XHRLayout } from '@/core/utils/xhr';
import get from 'lodash/get';
import axios from 'axios';

/** Organization interface API. */
export class OrganizationService {
  private xhr: XHRLayout = new XHRLayout();

  /**
   * Get list organizations.
   * @param {Data} data data.
   */
  public getList = async (data) => {
    const res = await this.xhr.post('/api/elevator/elevators', data);
    const content = res.content.map((item) => ({
      ...item,
      subject_type: get(item, 'subject.subject_type', ''),
      opf_name: get(item, 'subject.opf.name', ''),
      region: get(item, 'subject.address.oker.name_okato', ''),
      registration_date: item.registration_date ? item.registration_date.split(' ')[0] : '',
      exclusion_date: item.exclusion_date ? item.exclusion_date.split(' ')[0] : '',
      common_capacity: item.elevator_info.common_capacity ? item.elevator_info.common_capacity : '-',
    }));
    return {
      ...res,
      content,
    };
  };

  public getactualManufacturers = (data) => {
    return this.xhr.post(`/api/subject/manufacturer/actualManufacturers`, {
      queryString: data.queryString,
      page: data.page,
    });
  };

  /**
   * Get list request organization.
   * @param {Id} id data.
   */
  public getListRequests = (id) => {
    return this.xhr.get(`/api/elevator/elevator/${id}`);
  };

  /**
   * Change enabled select organization.
   * @param {Id} id data.
   */
  public changeEnable = (id) => {
    return this.xhr.get(`/api/elevator/changeEnable?elevatorId=${id}`);
  };

  /**
   * Get info about organization.
   * @param {Id} id data.
   */
  public getInfoOrganization = (id) => {
    return this.xhr.get(`/api/elevator-request/organizations/${id}`);
  };

  /**
   * Get actual info.
   * @param {Id} id data.
   */
  public getActualInfo = (id) => {
    return this.xhr.get(`/api/elevator-request/actualElevatorInfo?subjectId=${id}`);
  };

  /**
   * Search for organizations.
   * @param {params} params data.
   * @param {subjectType} subjectType data.
   */
  public searchOrganization = (params) => {
    return this.xhr.post('/api/subject/subjects', params);
  };
  /**
   * Search for organizations.
   * @param {query} query data.
   */
  public organizationField = (id) => {
    return this.xhr.get(`/api/subject/${id}`);
  };

  /**
   * Get organization list for the select.
   */
  public getElevatorSubject = (data?) => {
    return this.xhr.post('/api/elevator-request/subjects', data);
  };
  /**
   * Get document list for the select.
   */
  public getSubjectDocument = () => {
    return this.xhr.get('/api/subject/document_ident_type');
  };

  /**
   * Export organization.
   */
  public exportReport = async () => {
    const response = await axios.get('/api/elevator/elevator/exportReport', { responseType: 'blob' });
    return response;
  };
}
