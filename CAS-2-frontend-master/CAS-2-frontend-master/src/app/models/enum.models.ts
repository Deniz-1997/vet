import {constructByInterface} from '../utils/construct-by-interface';

export interface EnumInterface {
  id: string;
  name: string;
  disabled?: boolean;
}

export class EnumModel implements EnumInterface {
  id: string;
  name: string;
  disabled: boolean;

  constructor(o?: EnumInterface) {
    constructByInterface(o, this);
  }
}
