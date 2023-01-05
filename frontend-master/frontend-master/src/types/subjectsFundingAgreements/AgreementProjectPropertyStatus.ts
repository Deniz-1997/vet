import { State } from '@/types';

export enum AgreementProjectPropertyStatusCodes {
  BLANK = 'BLANK',
  PREPARE_SUBJ = 'PREPARE_SUBJ',
  COORDINATION_SUBJ = 'COORDINATION_SUBJ',
  AGREED_SUBJ = 'AGREED_SUBJ',
  SIGNED_SUBJ = 'SIGNED_SUBJ',
  COORDINATION_MSH = 'COORDINATION_MSH',
  REQUIRES_REVISION = 'REQUIRES_REVISION',
  REVISION_SUBJ = 'REVISION_SUBJ',
  AGREED_MSH = 'AGREED_MSH',
  APPROVED_MSH = 'APPROVED_MSH',
  TRANSFERRED = 'TRANSFERRED',
  CONCLUDED = 'CONCLUDED',
  DELETE = 'DELETE',
}

export type AgreementProjectPropertyStatus = State<AgreementProjectPropertyStatusCodes>;

export type PagingEntityAgreementProjectPropertyStatus = any;
