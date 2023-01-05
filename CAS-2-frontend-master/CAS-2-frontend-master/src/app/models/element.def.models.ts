import {constructByInterface} from '../utils/construct-by-interface';

export interface ElementDefInterface {
  id?: number | null;
  name?: string;
  deleted?: boolean;
}

export class ElementDef implements ElementDefInterface {
  id: number;
  name: string;
  deleted: boolean;

  constructor(o?: ElementDefInterface) {
    constructByInterface(o, this);
  }
}
