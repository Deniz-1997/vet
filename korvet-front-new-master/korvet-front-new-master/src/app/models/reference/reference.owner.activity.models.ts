import {constructByInterface} from '../../utils/construct-by-interface';

export interface ReferenceOwnerActivityInterface {
  id: number;
  name: string;
  deleted: boolean;
}

export class ReferenceOwnerActivityModel {
  'id': number;
  'name': string;
  'sort': number;
  'deleted': boolean;

  constructor(o?: ReferenceOwnerActivityInterface) {
    if (o) {
      constructByInterface(o, this);
    }
  }
}
