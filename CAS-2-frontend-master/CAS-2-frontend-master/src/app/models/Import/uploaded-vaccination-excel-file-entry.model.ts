import {constructByInterface} from '../../utils/construct-by-interface';
import {CasUserInterface, CasUserModel} from '../auth/cas-user.model';
import {SupervisoryAuthorityInterface, SupervisoryAuthorityModel} from '../contractors/supervisory-authority.model';
import {UploadedVaccinationExcelRowEntryInterface, UploadedVaccinationExcelRowEntryModel} from './uploaded-vaccination-excel-row-entry.model';

export interface UploadedVaccinationExcelFileEntryInterface {
  id: string;
  fixed: UploadedVaccinationExcelFileEntryInterface;
  hash: string;
  statusCode: string;
  statusMsg: string;
  sourceName: string;
  station: SupervisoryAuthorityInterface;
  lock: string;
  uploadedAt: string;
  uploadedBy: CasUserInterface;
  rows: Array<UploadedVaccinationExcelRowEntryInterface>;
  responseHash: string;
}

export class UploadedVaccinationExcelFileEntryModel implements UploadedVaccinationExcelFileEntryInterface {
  id: string;
  fixed: UploadedVaccinationExcelFileEntryModel;
  hash: string;
  statusCode: string;
  statusMsg: string;
  sourceName: string;
  station: SupervisoryAuthorityModel;
  lock: string;
  uploadedAt: string;
  uploadedBy: CasUserModel;
  rows: Array<UploadedVaccinationExcelRowEntryModel>;
  responseHash: string;

  constructor(o?: UploadedVaccinationExcelFileEntryInterface) {
    constructByInterface(o, this);
  }
}
