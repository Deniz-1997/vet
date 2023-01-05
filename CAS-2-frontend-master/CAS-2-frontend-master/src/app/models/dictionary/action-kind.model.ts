import {constructByInterface} from '../../utils/construct-by-interface';
import {ActionDataInterface} from './action-data.model';

export interface ActionKindInterface {
  id: string;
  name: string;
  sortOrder: number;
  actions: Array<ActionDataInterface>;
}

export class ActionKindModel implements ActionKindInterface {
  id: string;
  name: string;
  sortOrder: number;
  actions: Array<ActionDataInterface>;

  constructor(o?: ActionKindInterface) {
    constructByInterface(o, this);
  }
}
