import {constructByInterface} from '../utils/construct-by-interface';

export interface EnumTypeInterface {
  name: string;
  value: string;
}

export class EnumTypeModel implements EnumTypeInterface {
  name: string;
  value: string;
  code: string;
  title: string;

  constructor(o?: EnumTypeInterface) {
    constructByInterface(o, this);
  }
}
