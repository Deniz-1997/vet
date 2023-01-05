import {constructByInterface} from '../../utils/construct-by-interface';

export interface ReferenceProfessionInterface {
  id: number;
  name: string;
  sort: number;
  deleted: boolean;
}

export class ReferenceProfessionModel {
  id: number;
  name: string;
  sort: number;
  deleted: boolean;

  constructor(o?: ReferenceProfessionInterface) {
    constructByInterface(o, this);
  }
}
