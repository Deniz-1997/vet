import { XHRLayout } from '@/core/utils/xhr';
import mapInnerForm from './utils';
import axios from 'axios';

/** Template approval interface API. */
export class TemplateApprovalService {
  private xhr: XHRLayout = new XHRLayout();

  /**
   * Get list the template approval.
   * @param {data} data params for the filter.
   */
  public getListTemplate = (data) => {
    return this.xhr.post('/api/approval-template/templates', data);
  };

  /**
   * Get list for the types template.
   */
  public getListTemplateTypes = () => {
    return this.xhr.get('/api/approval-template/types');
  };

  /**
   * Get list approval organization.
   */
  public getListApprovalOrganization = (data?) => {
    return this.xhr.post('/api/subject/subjects', data);
  };

  public getListDivisions = (data) => {
    return this.xhr.post('/api/subject/division/find', data);
  }

  /**
   * Get list for the types template
   * @param {id} id id the select approval organizations.
   */
  public getListResponsible = (id) => {
    return this.xhr.get(`/api/approval-template/responsible?orgId=${id}`);
  };

  
  /**
   * Get list for the automatic stages
   */
   public getListAutomaticStages = () => {
    return this.xhr.get('/api/approval-template/automaticStages');
  };

  /**
   * Proccess activeted template
   * @param {data} form  
   */
  public activatedTemplate = (data) => {
    return this.xhr.post('/api/approval-template/activate', data);
  }

  /**
   * @param {data} data Data forms.
   */
  public createTemplate = (data) => {
    return this.xhr.post('/api/approval-template/create', {...data, status: {id: 1}});
  };

  /**
   * Get info about template approval.
   * @param {id} id id template.
   */
  public getInfoAboutTemplate = async (id) => {
    const response = await this.xhr.get(`/api/approval-template/templates/${id}`);
    const data = mapInnerForm(response);
    return data;
  };

  /**
   * @param {id} id id template;
   */
  public removeTemplate = (id) => {
    return this.xhr.post('/api/approval-template/delete', {id: id});
  };

  /**
   * @param {Params} params id template;
   */
  public exportReport = async (params) => {
    const response = await axios.post('/api/approval-template/exportReport', { params });
    const blob = new Blob([response.data], { type: "application/vnd.ms-excel" });
    return URL.createObjectURL(blob);
  };
};
