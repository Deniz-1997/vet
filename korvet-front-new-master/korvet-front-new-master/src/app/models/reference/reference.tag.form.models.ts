import {constructByInterface} from '../../utils/construct-by-interface';

export interface ReferenceTagFormInterface {
  id: number;
  name: string;
  sort: number;
  deleted: boolean;
}

export class ReferenceTagFormModel {
  'id': number;
  'name': string;
  'sort': number;
  'deleted': boolean;

  constructor(o?: ReferenceTagFormInterface) {
    constructByInterface(o, this);
  }
}
