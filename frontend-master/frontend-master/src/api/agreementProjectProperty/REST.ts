import { XHRLayout } from '@/core/utils/xhr';
import { AxiosResponse } from 'axios';

import {
  AgreementProjectPropertyRequest,
  AgreementProjectPropertyResponseData,
  AgreementProjectPropertyGetResponseData,
  AgreementProjectPropertyApprovalResponseData,
  AgreementProjectPropertiesValuesRequest,
  AgreementProjectPropertyPropertiesValuesResponseData,
} from './types';

const xhr: XHRLayout = new XHRLayout();

// eslint-disable-next-line max-len
export const createAgreementProjectProperty = async (payload: AgreementProjectPropertyRequest = {} as AgreementProjectPropertyRequest): Promise<AgreementProjectPropertyResponseData> => {
  const { data } = await xhr.post('/agreement-project-property/create', payload);

  return data;
};

export const getAgreementProjectProperty = async (id: number): Promise<AgreementProjectPropertyGetResponseData> => {
  return await xhr.get(`/agreement-project-property/${id}`);
};

// export const beginApprovalInRegion = async (measueId: number): Promise<AgreementProjectPropertyApprovalResponseData> => {
  export const beginApprovalInRegion = async (measueId: number) => {
  // return await xhr.patchOrError(`/agreement-project-property/${measueId}/begin-approval-in-region`);
  return await console.log('21234')
};

export const savePropertyValues = async (params: AgreementProjectPropertiesValuesRequest): Promise<AgreementProjectPropertyPropertiesValuesResponseData> => {
  const { measureId, propertyBudgetPeriodValues } = params;

  // const { data } = await xhr.patchOrError(`/agreement-project-property/${measureId}/property-values`, propertyBudgetPeriodValues);

  return '';
};
