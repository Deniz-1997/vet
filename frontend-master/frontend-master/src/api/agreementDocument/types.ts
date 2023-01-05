import { DocumentBytes, SignatureIdentifier } from '@/types';
import { CertificateInfoDocumentStamp } from '@/types/subjectsFundingAgreements/CertificateInfoDocumentStamp';
import { Signature } from '@/types/subjectsFundingAgreements/Signature';

export interface AgreementDocumentPreparationParams {
  measureId: number;
  certificateInfoDocumentStamp: CertificateInfoDocumentStamp;
}

export type AgreementDocumentPreparationResponseData = string;

export interface AgreementDocumentParams {
  measureId: number;
  fileUuid: string;
  isPdf?: boolean;
}

export type AgreementDocumentFormOrStoredResponseData = DocumentBytes;

export interface AgreementDocumentFormOrStoredParams {
  measureId: number;
  isPdf?: boolean;
  service: string;
  file?: any;
}

export type AgreementDocumentResponseData = DocumentBytes;

export interface AgreementDocumentSignParams {
  measureId: number;
  signature: Signature;
  certificate: any;
  pdf_image?: string;
  service: string;
}

export type AgreementDocumentSignResponseData = SignatureIdentifier;
