import {constructByInterface} from '../utils/construct-by-interface';

export interface DaDataInterface {
  name: string;
  surname: string;
  patronymic: string;
}

export class DaDataModel implements  DaDataInterface {
  name: string;
  surname: string;
  patronymic: string;

  constructor(o?: DaDataInterface) {
    constructByInterface(o, this);
  }
}

