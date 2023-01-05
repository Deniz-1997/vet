import { XHRLayout } from '@/core/utils/xhr';

/** NSI interface API. */
export class ContractsService {
  private xhr: XHRLayout = new XHRLayout();
  /**
    * Get list contracts.
    */
  public getList = (data) => {
    return this.xhr.post('/api/agent/contract/find', data);
  }

  /**
   * Get list request contracts.
   * @param {Id} id data.
   */
  public showContract = (id) => {
    return this.xhr.get(`/api/agent/contract/${id}`);
  }

  /**
   * Update contracts.
   */
  public updateContracts = (data) => {
    return this.xhr.post('/api/agent/contract/update', {
      ...data,
      subject: {
        ...data.subject,
        ...data.subject.subject
      },
      contract_date_start: data.contract_date_start,
      contract_date_end: data.contract_date_end,
      document: {
        ...data.document,
        doc_num: data.document.doc_num,
        doc_date: data.document.doc_date
      }
    });
  }

  /**
   * Create contracts.
   */
  public createContracts = (data) => {
    return this.xhr.post('/api/agent/contract/create', {
      subject: {
        ...data.subject,
        ...data.subject.subject
      },
      contract_date_start: data.contract_date_start,
      contract_date_end: data.contract_date_end,
      document: {
        doc_num: data.document.doc_num,
        doc_date: data.document.doc_date
      }
    });
  }

  /**
   * Export contracts.
   */
  public exportContracts = (data) => {
    return this.xhr.post('/api/agent/contract/exportReport', data);
  }

  /**
   * Update contracts.
   */
  public deleteContracts = (data) => {
    return this.xhr.post('/api/agent/contract/delete', data);
  }

}