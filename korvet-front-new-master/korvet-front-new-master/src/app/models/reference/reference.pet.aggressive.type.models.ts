import {constructByInterface} from '../../utils/construct-by-interface';

export interface ReferencePetAggressiveTypeInterface {
  id: number;
  name: string;
  deleted: boolean;
  level: number;
}

export class ReferencePetAggressiveTypeModel {
  id: number;
  name: string;
  deleted: boolean;
  level: number;

  constructor(o?: ReferencePetAggressiveTypeInterface) {
    if (o) {
      constructByInterface(o, this);
    }
  }
}
