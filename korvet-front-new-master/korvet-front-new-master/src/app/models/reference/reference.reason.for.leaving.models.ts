import {constructByInterface} from '../../utils/construct-by-interface';


export interface ReferenceReasonForLeavingInterface {
  id?: number;
  name: string;
  deleted?: boolean;
}

export class ReferenceReasonForLeavingModel implements ReferenceReasonForLeavingInterface {
  id?: number;
  name: string;
  deleted?: boolean;

  constructor(o?: ReferenceReasonForLeavingInterface) {
    if (o) {
      constructByInterface(o, this);
    }
  }
}
