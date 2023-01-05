import {constructByInterface} from '../../utils/construct-by-interface';

export interface ReferenceTagColorInterface {
  id: number;
  name: string;
  sort: number;
  deleted: boolean;
}

export class ReferenceTagColorModel {
  id: number;
  name: string;
  sort: number;
  deleted: boolean;

  constructor(o?: ReferenceTagColorInterface) {
    constructByInterface(o, this);
  }
}
