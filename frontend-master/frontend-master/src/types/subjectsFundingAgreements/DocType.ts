import { State } from '@/types/shared';

export enum DocTypeCodes {
  INDICATOR_AGREEMENT = 'INDICATOR_AGREEMENT',
  AGREEMENT = 'AGREEMENT',
  ADDITIONAL_AGREEMENЕ = 'ADDITIONAL_AGREEMENЕ',
}

export type DocType = State<DocTypeCodes>;
