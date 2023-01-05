import {constructByInterface} from '../../utils/construct-by-interface';
import {ReferenceUnitInterface} from './reference.unit.models';

export interface ReferenceStockInterface {
  id?: number;
  name: string;
  externalId: string;
  deleted: boolean;
  unit: ReferenceUnitInterface;
}

export class ReferenceStockModel implements ReferenceStockInterface {
  id: number;
  name: string;
  externalId: string;
  deleted: boolean;
  visible?: boolean;
  unit: ReferenceUnitInterface;

  constructor(o?: ReferenceStockInterface) {
    constructByInterface(o, this);
  }
}
