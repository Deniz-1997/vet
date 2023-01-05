import { AgreementProjectPropertyStatus } from './AgreementProjectPropertyStatus';
import { AgreementProjectPropertyStatusLog } from './AgreementProjectPropertyStatusLog';
import { AgreementProjectQuota } from './AgreementProjectQuota';
import { AgreementProjectSubsidy } from './AgreementProjectSubsidy';
import { DocType } from './DocType';
import { MeasureShort } from './Measures';
import { PropertyBudgetPeriodValues } from './PropertyBudgetPeriodValues';
import { Recipient } from './Recipient';

export interface AgreementProjectProperty {
  id: number;
  measure: MeasureShort;
  recipient: Recipient;
  quota: AgreementProjectQuota;
  subsidy: AgreementProjectSubsidy;
  docType: DocType;
  status: AgreementProjectPropertyStatus;
  docNumber: string;
  dateCreate: string;
  dateEdit: string;
  propertyBudgetPeriodValues: PropertyBudgetPeriodValues[];
  statusLogItems: AgreementProjectPropertyStatusLog[];
}
