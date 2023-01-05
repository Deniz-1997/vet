import { XHRLayout } from '@/core/utils/xhr';
import axios from 'axios';
import store from "@/store";

/** NSI interface API. */
export class AgentsService {
  private xhr: XHRLayout = new XHRLayout();
  /**
    * Get list agents.
    * @param {Data} data .
    */
  public getList = (data) => {
    return this.xhr.post('/api/subject/agent/agents', data);
  }

  /**
   * Get list request agents.
   * @param {Id} id data.
   */
  public getListAgents = (id) => {
    return this.xhr.get(`/api/subject/agent/agents/{id}?id=${id}`);
  }

  /**
   * Update agents.
   * @param {Data} data .
   */
  public updateAgents = (data) => {
    return this.xhr.post('/api/subject/agent/update', data);
  }

  /**
   * Create agents.
   * @param {Data} data .
   */
  public createAgents = (data) => {
    return this.xhr.post('/api/subject/agent/create', data);
  }

  /**
   * Export agents.
   */
  public exportAgents = async () => {
    const response = await axios.get('/api/agent/contract/exportReport', { responseType: 'blob' });
    
    store.commit('agents/setExportDocumentData', response.data);
    return URL.createObjectURL(response.data);
  }
}