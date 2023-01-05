import { XHRLayout } from '@/core/utils/xhr';
import axios from 'axios';

import {
  AgreementDocumentPreparationParams,
  AgreementDocumentPreparationResponseData,
  AgreementDocumentResponseData,
  AgreementDocumentSignParams,
  AgreementDocumentParams,
  AgreementDocumentFormOrStoredParams,
  AgreementDocumentFormOrStoredResponseData,
} from './types';

const ServiceMap = Object.freeze({
  'requests-answer': function (this: AgreementDocumentService, params: AgreementDocumentSignParams) {
    const { signature, certificate, measureId } = params;
    return this.xhr.post('/api/elevator-request/free/form/request/signature/save', {
      signature,
      certificate,
      id: measureId,
    });
  },
  requests: function (this: AgreementDocumentService, params: AgreementDocumentSignParams) {
    const { signature, certificate, measureId } = params;
    return this.xhr.post('/api/elevator-request/signature/save', {
      signature,
      certificate,
      id: measureId,
    });
  },
});

export class AgreementDocumentService {
  protected xhr: XHRLayout = new XHRLayout();

  public prepareDocumentForSigning = async (
    params: AgreementDocumentPreparationParams
  ): Promise<AgreementDocumentPreparationResponseData> => {
    const { measureId, certificateInfoDocumentStamp } = params;

    const { data } = await this.xhr.post(
      `/agreement-project-property/${measureId}/document/prepare-for-signing`,
      certificateInfoDocumentStamp
    );

    return data;
  };

  public getStoredDocument = async (params: AgreementDocumentParams): Promise<AgreementDocumentResponseData> => {
    const { measureId, fileUuid, isPdf = true } = params;

    const { data } = await this.xhr.get(`/agreement-project-property/${measureId}/document/${fileUuid}`, {
      headers: {
        accept: isPdf ? 'application/pdf' : 'text/plain',
      },
    });

    return data;
  };

  public getNewOrStoredDocument = async (
    params: AgreementDocumentFormOrStoredParams
  ): Promise<AgreementDocumentFormOrStoredResponseData> => {
    const { measureId = true, service, file } = params;
    if (file) {
      return await file.data;
    }
    const response = await axios.get(`/api/${service}/pdf/${measureId}`, { responseType: 'blob' });
    return response.data;
  };

  public signDocument = async (params: AgreementDocumentSignParams) => {
    const { measureId, signature, certificate, pdf_image, service } = params;
    if (ServiceMap[service]) {
      return ServiceMap[service].call(this, params);
    }

    const dataSign = await this.xhr.post(`/api/lot/signature/save`, {
      signature,
      certificate,
      pdf_image,
    });

    // todo: выяснить какой эндпоинт для сохранения подписи у сервиса 'elevator-request'
    if (service) {
      await this.xhr.get(`/api/${service}/sign/${measureId}/${dataSign.esp_id}`);
    }
    //
    return dataSign;
  };

  public getDocumentFromDescription = async (endpoint: string, params: any): Promise<any> => {
    const response = await axios.post(`/api/${endpoint}`, params, { responseType: 'blob' });
    return response.data;
  };

  public signDocumentFromDescription = async (params) => {
    const { service, id, esp_id } = params;

    return await this.xhr.post(`/api/${service}/${id}/${esp_id}`);
  };
}
