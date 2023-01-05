import {constructByInterface} from '../../utils/construct-by-interface';

export interface FtpHistoryInterface {
  id: number;
  fileName: string;
  operationType: string;
  date: string;
}

export class FtpHistoryModel {
  id: number;
  fileName: string;
  operationType: string;
  date: string;

  constructor(o?: FtpHistoryInterface) {
    if (o) {
      constructByInterface(o, this);
    }
  }
}
