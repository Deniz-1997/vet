import {constructByInterface} from '../../utils/construct-by-interface';

export interface ReferenceShelterInterface {
  id: number;
  name: string;
  sort: number;
  deleted: boolean;
}

export class ReferenceShelterModel {
  id: number;
  name: string;
  sort: number;
  deleted: boolean;

  constructor(o?: ReferenceShelterInterface) {
    constructByInterface(o, this);
  }
}
