import { EImportProcessType, EImportStatus } from '../enums/import';
import { TInnerFilter } from './common';

export type TImportResponseItem = {
  id: number;
  processName: EImportProcessType;
  fileName: string;
  status: EImportStatus;
  result?: string;
  errors?: string;
  created: string;
  endDate?: string;
};

export type TImportInnerFilter = TInnerFilter & { processName: EImportProcessType };
