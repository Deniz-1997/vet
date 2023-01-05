import {constructByInterface} from '../../utils/construct-by-interface';

export interface ReferencePetIdentifierTypeInterface {
  id: number;
  name: string;
  deleted: boolean;
}

export class ReferencePetIdentifierTypeModel {
  id: number;
  name: string;
  deleted: boolean;
  type: any;
  value: any;

  constructor(o?: ReferencePetIdentifierTypeInterface) {
    if (o) {
      constructByInterface(o, this);
    }
  }
}
