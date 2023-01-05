import {constructByInterface} from '../../utils/construct-by-interface';

export interface ReferenceOwnerLegalFormInterface {
  id: number;
  name: string;
  deleted: boolean;
}

export class ReferenceOwnerLegalFormModel {
  id: number;
  name: string;
  deleted: boolean;

  constructor(o?: ReferenceOwnerLegalFormInterface) {
    if (o) {
      constructByInterface(o, this);
    }
  }
}
