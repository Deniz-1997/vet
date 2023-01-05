import {constructByInterface} from '../../utils/construct-by-interface';

export interface ReferenceOrganizationInterface {
  id?: number;
  name: string;
  deleted: boolean;
  inn: string;
  type: string;
}

export class ReferenceOrganizationModel implements ReferenceOrganizationInterface {
  id: number;
  name: string;
  deleted: boolean;
  inn: string;
  type: string;

  constructor(o?: ReferenceOrganizationInterface) {
    constructByInterface(o, this);
  }
}
