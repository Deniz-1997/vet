import {constructByInterface} from '../../../utils/construct-by-interface';

export interface ReportsListInterface {

  name: string;
  id: string;
  uuidTmp: string;
}

export class ReportsListModel implements ReportsListInterface {
  name: string;
  id: string;
  uuidTmp: string;


  constructor(o?: ReportsListInterface) {
    constructByInterface(o, this);
  }
}
