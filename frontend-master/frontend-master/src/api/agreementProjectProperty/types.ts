import { AgreementProjectProperty } from '@/types/subjectsFundingAgreements/AgreementProjectProperty';
import { AgreementProjectPropertyCreateResult } from '@/types/subjectsFundingAgreements/AgreementProjectPropertyCreateResult';
import { PropertyBudgetPeriodValues } from '@/types/subjectsFundingAgreements/PropertyBudgetPeriodValues';

export type AgreementProjectPropertyRequest = {
  limitId: number;
}[]

export type AgreementProjectPropertyResponseData = AgreementProjectPropertyCreateResult[];

export type AgreementProjectPropertyGetResponseData = AgreementProjectProperty;

export type AgreementProjectPropertyApprovalResponseData = string;

export interface AgreementProjectPropertiesValuesRequest {
  measureId: number;
  propertyBudgetPeriodValues: PropertyBudgetPeriodValues[]
}

export type AgreementProjectPropertyPropertiesValuesResponseData = string;
