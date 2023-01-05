import {constructByInterface} from '../../utils/construct-by-interface';

export interface PrintReportInterface {
  stockId: number;
  date: string;
}

export class PrintReportModel implements PrintReportInterface {
  stockId: number;
  date: string;

  constructor(o?: PrintReportInterface) {
    constructByInterface(o, this);
  }
}
