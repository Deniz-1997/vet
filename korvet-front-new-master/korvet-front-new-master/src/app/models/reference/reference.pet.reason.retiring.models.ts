import {constructByInterface} from '../../utils/construct-by-interface';


export interface ReferencePetReasonRetiringInterface {
  id: number;
  name: string;
  deleted: boolean;
  sort: number;
}

export class ReferencePetReasonRetiringModel {
  id: number;
  name: string;
  deleted: boolean;
  sort: number;

  constructor(o?: ReferencePetReasonRetiringInterface) {
    if (o) {
      constructByInterface(o, this);
    }
  }
}
