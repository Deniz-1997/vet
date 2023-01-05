import {constructByInterface} from '../../utils/construct-by-interface';

export interface PrintFormsInterface {
  id?: number;
  name: string;
  enabled: boolean;
  file: string;
  type: string;
}

export class PrintFormsModel implements PrintFormsInterface {
  id: number;
  name: string;
  enabled: boolean;
  file: string;
  type: string;

  constructor(o?: PrintFormsInterface) {
    constructByInterface(o, this);
  }
}
