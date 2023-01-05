import axios from 'axios';

export class PrintDocumentService {
  public getDocument = async (params: { measureId: number; service: string }): Promise<any> => {
    const { measureId, service } = params;
    const response = await axios.get(`/api/${service}/${measureId}`, { responseType: 'blob' });
    return response.data;
  };
}
