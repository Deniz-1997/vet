import { XHRLayout } from '@/core/utils/xhr';
import store from '@/store';
import axios from 'axios';
import { mapInnerForm, mapForm, mapInnerChangeForm, mapInnerFormForOrganization } from './utils';

/** Elevators interface API. */
export class ElevatorsService {
  private xhr: XHRLayout = new XHRLayout();

  /**
   * Get request info.
   * @param {Id} id id elevators.
   */
  public getElevatorInfo = async (id) => {
    const response = await this.xhr.get(`/api/elevator-request/requests/${id}`);
    const data = mapInnerForm(response);
    return data;
  };

  /**
   * Get elevator info.
   * @param {Id} id id elevators.
   */
  public getInfoElevatorForCardOrganization = async (id) => {
    const response = await this.xhr.get(`/api/elevator/elevator/${id}`);
    const data = mapInnerFormForOrganization(response);
    return data;
  };

  /**
   * Get elevator info.
   * @param {Id} id id elevators.
   */
  public getActualInfoElevator = async (id) => {
    const response = await this.xhr.get(`/api/elevator-request/create_request_for_organization/${id}`);
    const data = mapInnerForm(response);
    return data;
  };

  /**
   * Get list applications.
   */
  public getListRequests = (data) => {
    return this.xhr.post(`/api/elevator-request/requests`, data);
  };

  /**
   * Get list insurance document type.
   */
  public getListInsuranceDocument = () => {
    return this.xhr.get('/api/elevator-request/documentType');
  };

  /**
   * Get list usage rights
   */
  public getListUsageRights = () => {
    return this.xhr.get('/api/elevator-request/usageRights');
  };

  /**
   * Get list granary type.
   */
  public getListGranaryType = (data) => {
    return this.xhr.post('/api/nci/granaryType', data);
  };

  /**
   * Get list storage method.
   */
  public getListStorageMethod = (data) => {
    return this.xhr.post('/api/nci/storageMethod', data);
  };

  /**
   * Get list proccessing methods.
   */
  public getListProccessingMethod = (data) => {
    return this.xhr.post('/api/nci/processingMethod', data);
  };

  /**
   * Get list railway station.
   */
  public getListRailwayStation = (data?) => {
    return this.xhr.post('/api/elevator-request/railwayStation', data);
  };

  /**
   * Get list service type.
   */
  public getListServiceType = () => {
    return this.xhr.get('/api/elevator-request/serviceTypes');
  };

  /**
   * Create new request.
   * @param {data} data form request.
   */
  public create = (data) => {
    const form = mapForm(data);
    return this.xhr.post('/api/elevator-request/create', form);
  };

  /**
   * Update the request.
   * @param {data} data form request.
   */
  public update = (data) => {
    const form = mapForm(data);
    return this.xhr.post('/api/elevator-request/update', form);
  };

  /**
   * Delete a draft request.
   * @param {id} id id for the draft request.
   */
  public delete = (id) => {
    return this.xhr.get(`/api/elevator-request/delete/${id}`);
  };

  /**
   * Get request type for the organization.
   * @param {id} id id for the select organization.
   */
  public getRequestType = (id) => {
    return this.xhr.get(`/api/elevator-request/requestType/${id}`);
  };

  /**
   * Get changes request.
   * @param {id} id id for the select request.
   */
  public getChangesRequest = async (id) => {
    const response = await this.xhr.get(`/api/elevator-request/requestChanges/${id}`);
    const data = mapInnerChangeForm(response);
    return data;
  };

  /**
   * Get changes request.
   * @param {id} id id for the select request.
   */
  public getRequestChangesList = (id: string) => {
    return this.xhr.get(`/api/elevator-request/requestChangesList?requestId=${id}`);
  };

  /**
   * Send approve the request.
   * @param {id} id id for the request.
   */
  public sendApproval = (id) => {
    return this.xhr.get(`/api/elevator-request/sendApproval/${id}`);
  };

  /**
   * Exclude the organinzation.
   * @param {reasonId} reasonId .
   * @param {id} id .
   * @param {date} .
   */
  public excludeOrganization = (data) => {
    return this.xhr.post('/api/elevator/exclude', data.params);
  };

  /**
   * Fetch reason list.
   */
  public reasonList = () => {
    return this.xhr.get('/api/elevator/reason');
  };

  /**
   * Get a statement og the organization
   * @param {id} id identificator elevator.
   */
  public getStatement = async (id) => {
    axios
      .get(`/api/elevator/elevator/exportInfo/${id}`, {
        responseType: 'blob',
      })
      .then((res) => {
        const blob = new Blob([res.data], { type: 'application/pdf' });
        window.open(URL.createObjectURL(blob), '_blank');
      });
  };

  /**
   * Export agents.
   */
  public exportReport = async () => {
    const response = await axios.get('/api/elevator-request/exportReport', { responseType: 'blob' });
    const blob = new Blob([response.data], { type: 'application/vnd.ms-excel' });
    return store.commit('elevator/setExportDocumentData', URL.createObjectURL(blob));
  };
}
