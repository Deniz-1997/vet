import {constructByInterface} from '../../utils/construct-by-interface';
import {VaccinationInterface, VaccinationModel} from '../vaccination/vaccination.model';
import {UploadedVaccinationExcelFileEntryInterface, UploadedVaccinationExcelFileEntryModel} from './uploaded-vaccination-excel-file-entry.model';

export interface UploadedVaccinationExcelRowEntryInterface {
  id: string;
  status: {
    code: string;
    title: string;
  };
  statusMsg: string;
  parsedAt: string;
  processedAt: string;
  rowNumber: number;
  data: string;
  vaccination: VaccinationInterface;
  excelFile: UploadedVaccinationExcelFileEntryInterface;
}

export class UploadedVaccinationExcelRowEntryModel implements UploadedVaccinationExcelRowEntryInterface {
    id: string;
    status: {
      code: string;
      title: string;
    };
    statusMsg: string;
    parsedAt: string;
    processedAt: string;
    rowNumber: number;
    data: string;
    vaccination: VaccinationModel;
    excelFile: UploadedVaccinationExcelFileEntryModel;

  constructor(o?: UploadedVaccinationExcelRowEntryInterface) {
    constructByInterface(o, this);
  }
}
