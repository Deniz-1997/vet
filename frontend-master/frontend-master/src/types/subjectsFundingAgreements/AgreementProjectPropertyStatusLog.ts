import { AgreementProjectPropertyDocumentLink } from './AgreementProjectPropertyDocumentLink';
import { AgreementProjectPropertyStatus } from './AgreementProjectPropertyStatus';

export interface AgreementProjectPropertyStatusLog {
  id: number;
  status: AgreementProjectPropertyStatus;
  dateFrom: string;
  dateTo: string;
  document: AgreementProjectPropertyDocumentLink;
}
