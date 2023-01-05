import {constructByInterface} from '../utils/construct-by-interface';

export interface EntityInterface {
  name: string;
  className: string;
}

export class EntityModel implements EntityInterface {
  name: string;
  className: string;

  constructor(o?: EntityInterface) {
    constructByInterface(o, this);
  }
}
