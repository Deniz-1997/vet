import {constructByInterface} from '../../utils/construct-by-interface';

export interface ReferencePetTypeInterface {
  id: number;
  name: string;
  deleted: boolean;
}

export class ReferencePetTypeModel {
  'id': number;
  'name': string;
  'deleted': boolean;

  constructor(o?: ReferencePetTypeInterface) {
    constructByInterface(o, this);
  }
}
