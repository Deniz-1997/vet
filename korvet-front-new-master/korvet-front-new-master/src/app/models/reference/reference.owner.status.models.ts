import {constructByInterface} from '../../utils/construct-by-interface';

export interface ReferenceOwnerStatusInterface {
  id?: number;
  name: string;
  deleted?: boolean;
}

export class ReferenceOwnerStatusModel {
  id?: number;
  name: string;
  deleted?: boolean;

  constructor(o?: ReferenceOwnerStatusInterface) {
    if (o) {
      constructByInterface(o, this);
    }
  }
}
