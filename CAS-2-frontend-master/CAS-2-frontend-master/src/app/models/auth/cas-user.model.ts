import {constructByInterface} from '../../utils/construct-by-interface';

export interface CasUserInterface {
  id: string;
}

export class CasUserModel implements CasUserInterface {
  id: string;

  constructor(o?: CasUserInterface) {
    constructByInterface(o, this);
  }
}
