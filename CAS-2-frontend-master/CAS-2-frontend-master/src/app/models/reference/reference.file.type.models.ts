import {constructByInterface} from '../../utils/construct-by-interface';

export interface ReferenceFileTypeInterface {
  id: number;
  name: string;
  deleted: boolean;
}

export class ReferenceFileTypeModel {
  'id': number;
  'name': string;
  'deleted': boolean;

  constructor(o?: ReferenceFileTypeInterface) {
    if (o) {
      constructByInterface(o, this);
    }
  }
}
